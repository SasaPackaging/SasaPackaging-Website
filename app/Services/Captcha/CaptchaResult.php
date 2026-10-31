<?php

namespace App\Services\Captcha;

/**
 * The answer of one captcha check. Three possible outcomes:
 *
 *  passed       the visitor is accepted
 *  failed       the visitor's token is missing / wrong / expired / already used  -> ask them to try again
 *  unavailable  we could not decide (Cloudflare unreachable, or the keys in .env are wrong)  -> our problem, not theirs
 */
final class CaptchaResult
{
    const PASSED      = 'passed';
    const FAILED      = 'failed';
    const UNAVAILABLE = 'unavailable';

    /** @var string */
    private $outcome;

    /** @var string[] */
    private $errors;

    private function __construct($outcome, array $errors)
    {
        $this->outcome = $outcome;
        $this->errors  = $errors;
    }

    public static function passed()
    {
        return new self(self::PASSED, []);
    }

    public static function failed(array $errors = [])
    {
        return new self(self::FAILED, $errors);
    }

    public static function unavailable(array $errors = [])
    {
        return new self(self::UNAVAILABLE, $errors);
    }

    public function isPassed()
    {
        return $this->outcome === self::PASSED;
    }

    public function isUnavailable()
    {
        return $this->outcome === self::UNAVAILABLE;
    }

    /** Short error codes (Cloudflare's own, or ours). Safe to log - they never contain the token. */
    public function errors()
    {
        return $this->errors;
    }

    public function hasError($code)
    {
        return in_array($code, $this->errors, true);
    }
}
