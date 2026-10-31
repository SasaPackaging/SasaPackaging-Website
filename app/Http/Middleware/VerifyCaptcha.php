<?php

namespace App\Http\Middleware;

use App\Services\Captcha\TurnstileVerifier;
use Closure;
use Illuminate\Support\Facades\Log;

/**
 * Route middleware "captcha:<form>" - lets a request through only if its Turnstile token checks out.
 *
 *   Route::post('/send_quote', ...)->middleware(['throttle:10,1', 'captcha:quote']);
 *
 * It runs BEFORE the form is validated, so a bot is turned away before any field checks,
 * file scanning, database or email work happens. Answers use the same JSON shape as the
 * rest of the public forms ({ status, message }) so sasa-forms.js shows them as a normal message.
 */
class VerifyCaptcha
{
    /** @var TurnstileVerifier */
    protected $verifier;

    public function __construct(TurnstileVerifier $verifier)
    {
        $this->verifier = $verifier;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string|null              $action  which form this is ("contact", "quote"); must match the widget's data-action
     */
    public function handle($request, Closure $next, $action = null)
    {
        if (!config('captcha.enabled')) {
            return $next($request);
        }

        $result = $this->verifier->verify(
            $request->input(config('captcha.field', 'cf-turnstile-response')),
            $request->ip(),
            $action
        );

        if ($result->isPassed()) {
            return $next($request);
        }

        // We could not decide (Cloudflare down / keys wrong). Details are already in the log.
        if ($result->isUnavailable()) {
            if (config('captcha.fail_open')) {
                Log::warning('Captcha: check skipped because it was unavailable and CAPTCHA_FAIL_OPEN=true.', ['action' => $action]);

                return $next($request);
            }

            return $this->reject(__('inbox.error.captcha_unavailable'), 503);
        }

        Log::info('Captcha: submission refused.', [
            'action' => $action,
            'ip'     => $request->ip(),
            'reason' => implode(',', $result->errors()),
        ]);

        return $this->reject(
            $result->hasError('missing-input-response') ? __('inbox.error.captcha_required') : __('inbox.error.captcha_failed'),
            422
        );
    }

    protected function reject($message, $status)
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'captcha' => true,
        ], $status);
    }
}
