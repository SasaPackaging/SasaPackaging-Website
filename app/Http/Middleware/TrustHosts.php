<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

/**
 * Optional protection against fake "Host" headers (links in emails / redirects built from a forged host).
 *
 * It is OFF until you set this in the live .env file:
 *     TRUSTED_HOSTS=sasapackaging.com,www.sasapackaging.com
 * (comma separated, host names only - no https://). Leave it empty and nothing changes.
 */
class TrustHosts extends Middleware
{
    public function hosts()
    {
        $list = array_filter(array_map('trim', explode(',', (string) env('TRUSTED_HOSTS', ''))));

        return array_map(function ($host) {
            return '^' . preg_quote(strtolower($host), '/') . '$';
        }, $list);
    }
}
