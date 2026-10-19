<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

/**
 * Keeps visitors out of the admin panel: anyone not logged in as an admin goes to the admin login page.
 */
class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        return parent::handle($request, $next, 'admin');
    }

    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('admin.login');
        }
    }
}
