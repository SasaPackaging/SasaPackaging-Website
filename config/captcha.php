<?php

/*
|--------------------------------------------------------------------------
| Captcha (Cloudflare Turnstile) for the public Contact and Quote forms
|--------------------------------------------------------------------------
|
| Every value below is read from the .env file - never put keys in this file.
| See CAPTCHA-SETUP.md for the step-by-step setup.
|
*/

return [

    // Master switch. Leave it ON (true). Turning it off removes the protection.
    'enabled' => (bool) env('CAPTCHA_ENABLED', true),

    // Public key (safe to appear in the page's HTML) and secret key (server only, NEVER shown to visitors).
    'site_key'   => env('TURNSTILE_SITE_KEY'),
    'secret_key' => env('TURNSTILE_SECRET_KEY'),

    // Where the server asks Cloudflare "is this visitor's token genuine?"
    'verify_url' => 'https://challenges.cloudflare.com/turnstile/v0/siteverify',

    // Seconds to wait for Cloudflare before giving up.
    'timeout' => 5,

    // What to do when Cloudflare cannot be reached (or the keys are wrong):
    //   false (recommended) = refuse the form, the visitor is asked to try again  -> bots cannot slip in
    //   true                = let the form through unchecked                      -> never loses a lead, but unprotected
    'fail_open' => (bool) env('CAPTCHA_FAIL_OPEN', false),

    // The token must have been created on this website = the host in APP_URL (with or without "www.").
    // Blocks tokens made on other sites. Extra allowed domains, comma separated, only if the site
    // is also served from another hostname (e.g. a staging domain).
    'verify_hostname'   => true,
    'allowed_hostnames' => array_values(array_filter(array_map('trim', explode(',', (string) env('CAPTCHA_ALLOWED_HOSTNAMES', ''))))),

    // Name of the hidden field the Turnstile widget adds to the form. Do not change.
    'field' => 'cf-turnstile-response',

    // Look of the widget:  theme = light | dark | auto
    //                      appearance = always (always visible) | interaction-only (hidden unless Cloudflare needs a click)
    'theme'      => env('TURNSTILE_THEME', 'light'),
    'appearance' => env('TURNSTILE_APPEARANCE', 'always'),
];
