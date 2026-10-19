<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // Admin panel: an expired / stale form (HTTP 419 "Page Expired") must not be a dead end.
        // This happens e.g. when the login page is submitted twice or opened with the Back button.
        $this->renderable(function (HttpException $e, $request) {
            if ($e->getStatusCode() !== 419 || !$request->is(trim(config('admin.prefix'), '/') . '/*')) {
                return null; // not an admin page: keep Laravel's normal behaviour
            }

            if (Auth::guard('admin')->check()) {
                return redirect()->route('admin.dashboard')->with('info', 'You are already signed in.');
            }

            return redirect()->route('admin.login')->with('error', 'That page expired. Please sign in again.');
        });
    }
}
