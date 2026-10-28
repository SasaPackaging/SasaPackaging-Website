<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Adds simple, safe security headers to every response.
 *
 *  - X-Frame-Options          : other websites cannot show our pages inside a frame (clickjacking)
 *  - X-Content-Type-Options   : browsers must not "guess" a file type (stops disguised uploads)
 *  - Referrer-Policy          : do not leak full page addresses to other sites
 *  - Permissions-Policy       : camera / microphone / location are not used by this site
 *  - Strict-Transport-Security: only when the page was opened over HTTPS
 *  - no-store on the admin    : after logout the Back button cannot show private pages
 *
 * A Content-Security-Policy is NOT added on purpose: the site uses inline scripts / styles,
 * so a strict policy would break it. It can be added later once those are moved to files.
 */
class SecurityHeaders
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // anything that is not a normal HTTP response object is left alone
        if (!isset($response->headers)) {
            return $response;
        }

        $headers = [
            'X-Frame-Options'        => 'SAMEORIGIN',
            'X-Content-Type-Options' => 'nosniff',
            'Referrer-Policy'        => 'strict-origin-when-cross-origin',
            'Permissions-Policy'     => 'camera=(), microphone=(), geolocation=()',
        ];

        if ($request->isSecure()) {
            $headers['Strict-Transport-Security'] = 'max-age=31536000';
        }

        foreach ($headers as $name => $value) {
            if (!$response->headers->has($name)) {
                $response->headers->set($name, $value);
            }
        }

        if ($request->is(trim(config('admin.prefix', 'admin'), '/'), trim(config('admin.prefix', 'admin'), '/') . '/*')) {
            $response->headers->set('Cache-Control', 'no-store, private');
        }

        return $response;
    }
}
