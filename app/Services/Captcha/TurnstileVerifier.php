<?php

namespace App\Services\Captcha;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Asks Cloudflare whether a Turnstile token (created in the visitor's browser) is genuine.
 *
 * The check is only trustworthy on the SERVER: the browser can be faked, Cloudflare's answer cannot.
 * A token works exactly once and expires after 5 minutes - Cloudflare enforces that for us.
 */
class TurnstileVerifier
{
    /** Cloudflare never issues tokens longer than this, so anything longer is junk and is not even sent. */
    const MAX_TOKEN_LENGTH = 2048;

    /**
     * @param mixed       $token          value of the "cf-turnstile-response" field (anything the visitor sent)
     * @param string|null $ip             visitor's IP address (helps Cloudflare's decision)
     * @param string|null $expectedAction "contact" / "quote": the token must have been made for that form
     *
     * The token must also have been made on THIS website: the host of APP_URL (with or without "www."),
     * plus any CAPTCHA_ALLOWED_HOSTNAMES. We deliberately do not trust the visitor's "Host" header for this.
     */
    public function verify($token, $ip = null, $expectedAction = null)
    {
        $secret = trim((string) config('captcha.secret_key'));

        // Keys missing in .env = our configuration problem. Be loud in the log, never silently skip the check.
        if ($secret === '' || trim((string) config('captcha.site_key')) === '') {
            Log::critical('Captcha: TURNSTILE_SITE_KEY / TURNSTILE_SECRET_KEY are not set in .env.');

            return CaptchaResult::unavailable(['missing-configuration']);
        }

        // Cloudflare's published TEST keys (they approve everybody) are for local development only.
        $testKeys = $this->isTestKey($secret);

        if ($testKeys && app()->environment('production')) {
            Log::critical('Captcha: Cloudflare TEST keys are set on a production site. They protect nothing - put the real keys in .env.');

            return CaptchaResult::unavailable(['test-keys-in-production']);
        }

        // Not a string / empty / absurdly long: cannot be a real token, so do not spend a network call on it.
        if (!is_string($token) || $token === '' || strlen($token) > self::MAX_TOKEN_LENGTH) {
            return CaptchaResult::failed(['missing-input-response']);
        }

        $payload = ['secret' => $secret, 'response' => $token];
        if (is_string($ip) && $ip !== '') {
            $payload['remoteip'] = $ip;
        }

        try {
            $response = Http::asForm()
                ->acceptJson()
                ->timeout(max(1, (int) config('captcha.timeout', 5)))
                ->post((string) config('captcha.verify_url'), $payload);
        } catch (Throwable $e) {
            // The secret travels in the POST body, so it can never appear in this message.
            Log::error('Captcha: could not reach Cloudflare: ' . $e->getMessage());

            return CaptchaResult::unavailable(['network-error']);
        }

        $body = $response->json();

        if (!is_array($body)) {
            Log::error('Captcha: unreadable answer from Cloudflare (HTTP ' . $response->status() . ').');

            return CaptchaResult::unavailable(['bad-response']);
        }

        if (empty($body['success'])) {
            $codes = (isset($body['error-codes']) && is_array($body['error-codes']))
                ? array_values(array_filter($body['error-codes'], 'is_string'))
                : [];

            // Wrong secret key, or Cloudflare itself is having a problem: not the visitor's fault.
            if (array_intersect($codes, ['missing-input-secret', 'invalid-input-secret'])) {
                Log::critical('Captcha: Cloudflare rejected our secret key. Check TURNSTILE_SECRET_KEY in .env.');

                return CaptchaResult::unavailable($codes);
            }
            if (in_array('internal-error', $codes, true)) {
                Log::error('Captcha: Cloudflare reported an internal error.');

                return CaptchaResult::unavailable($codes);
            }

            // Anything else (invalid-input-response, timeout-or-duplicate ...): the visitor's token is not valid.
            return CaptchaResult::failed($codes ?: ['verification-failed']);
        }

        // Cloudflare says the token is genuine. Now make sure it was made for THIS site and THIS form.
        // (Skipped for Cloudflare's test keys, whose fake answers carry placeholder values.)
        if (!$testKeys) {
            if (is_string($expectedAction) && $expectedAction !== '') {
                $action = isset($body['action']) ? (string) $body['action'] : '';

                if (!hash_equals($expectedAction, $action)) {
                    return CaptchaResult::failed(['action-mismatch']);
                }
            }

            if (config('captcha.verify_hostname', true)) {
                $hostname = strtolower(isset($body['hostname']) ? (string) $body['hostname'] : '');
                $allowed  = $this->allowedHostnames();

                if (!in_array($hostname, $allowed, true)) {
                    // A hostname is not a secret. Logging it makes a wrong APP_URL easy to spot and fix.
                    Log::warning('Captcha: token was created on "' . $hostname . '" but only these hosts are accepted: '
                        . implode(', ', $allowed) . '. Check APP_URL in .env (or set CAPTCHA_ALLOWED_HOSTNAMES).');

                    return CaptchaResult::failed(['hostname-mismatch']);
                }
            }
        }

        return CaptchaResult::passed();
    }

    /** Hosts a genuine token may come from: APP_URL's host, the same host with / without "www.", and the extra list from .env. */
    protected function allowedHostnames()
    {
        $hosts   = (array) config('captcha.allowed_hostnames', []);
        $appHost = parse_url((string) config('app.url'), PHP_URL_HOST);

        if (is_string($appHost) && $appHost !== '') {
            $appHost = strtolower($appHost);
            $hosts[] = $appHost;
            $hosts[] = strpos($appHost, 'www.') === 0 ? substr($appHost, 4) : 'www.' . $appHost;
        }

        return array_values(array_unique(array_map('strtolower', $hosts)));
    }

    /** Cloudflare's dummy keys look like 1x0000000000000000000000000000000AA (a real key never does). */
    protected function isTestKey($key)
    {
        return preg_match('/^[123]x0{10,}/', $key) === 1;
    }
}
