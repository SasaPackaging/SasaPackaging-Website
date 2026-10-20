<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Route guard by role:  ->middleware('admin.can:inbox')
 * An admin whose role does not include the permission is sent back to the dashboard.
 */
class Permission
{
    public function handle($request, Closure $next, $permission)
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin || !$admin->hasPermission($permission)) {
            return redirect()->route('admin.dashboard')->with('error', 'You do not have access to that section.');
        }

        return $next($request);
    }
}
