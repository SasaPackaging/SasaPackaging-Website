<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Admin login / logout.
 *
 * Brute-force protection (on top of the route's "throttle:6,1"):
 * after MAX_ATTEMPTS wrong passwords for the same email from the same visitor, login is paused for LOCK_SECONDS.
 */
class AuthController extends Controller
{
    const MAX_ATTEMPTS = 5;
    const LOCK_SECONDS = 900; // 15 minutes

    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(LoginRequest $request)
    {
        $key = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            $minutes = (int) ceil(RateLimiter::availableIn($key) / 60);

            Log::warning('Admin login locked', ['email' => $request->input('email'), 'ip' => $request->ip()]);

            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'Too many failed attempts. Please try again in ' . max(1, $minutes) . ' minute(s).']);
        }

        $credentials = $request->only('email', 'password');

        try {
            $loggedIn = Auth::guard('admin')->attempt($credentials, $request->boolean('remember'));
        } catch (\Throwable $e) {
            // e.g. the database is down: show a calm message instead of a crash page
            report($e);

            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'Login is not available right now. Please try again in a few minutes.']);
        }

        if (!$loggedIn) {
            RateLimiter::hit($key, self::LOCK_SECONDS);

            Log::notice('Admin login failed', ['email' => $request->input('email'), 'ip' => $request->ip()]);

            // same message for "unknown email" and "wrong password" so nobody can find out which emails exist
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'These details do not match our records.']);
        }

        RateLimiter::clear($key);

        // new session id after login (stops "session fixation")
        $request->session()->regenerate();

        try {
            Auth::guard('admin')->user()->forceFill(['last_login_at' => now()])->save();
        } catch (\Throwable $e) {
            report($e); // the date is only informational, it must never block a successful login
        }

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    protected function throttleKey(Request $request)
    {
        return 'admin-login|' . mb_strtolower((string) $request->input('email')) . '|' . $request->ip();
    }
}
