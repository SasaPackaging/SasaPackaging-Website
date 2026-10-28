<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler extends ExceptionHandler
{
    /**
     * Addresses of the public forms. They are sent by JavaScript (sasa-forms.js / sasa-careers.js)
     * which reads  { status, message }  - so even a crash must answer in that shape.
     */
    protected $formEndpoints = ['send_quote', 'common/contact_process', 'careers/apply'];

    /**
     * Exceptions that are normal (wrong input, not logged in ...) and would only fill the log file.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        TokenMismatchException::class,
        ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
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
        // 1) Public forms: ANY failure answers as JSON  { status: 'error', message }  - never an HTML error page.
        //    (Real errors are still written to storage/logs by report().)
        $this->renderable(function (\Throwable $e, $request) {
            if (!$request->isMethod('post') || !$request->is(...$this->formEndpoints)) {
                return null;
            }

            // Validation errors already have their own JSON answer (see SiteFormRequest / CareerController)
            if ($e instanceof ValidationException || $e instanceof HttpResponseException) {
                return null;
            }

            [$status, $message] = $this->formFailure($e);

            return response()->json(['status' => 'error', 'message' => $message], $status);
        });

        // 2) Admin panel: an expired / stale form (HTTP 419 "Page Expired") must not be a dead end.
        //    This happens e.g. when the login page is submitted twice or opened with the Back button.
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

    /**
     * What the visitor reads for each kind of failure on a public form: [http status, message].
     * Technical details are never shown; they only go to the log.
     */
    protected function formFailure(\Throwable $e)
    {
        if ($e instanceof PostTooLargeException) {
            return [413, 'The file is too large. Please upload a smaller file.'];
        }
        if ($e instanceof TokenMismatchException) {
            return [419, 'Your session expired. Please refresh the page and try again.'];
        }
        if ($e instanceof HttpExceptionInterface) {
            switch ($e->getStatusCode()) {
                case 419:
                    return [419, 'Your session expired. Please refresh the page and try again.'];
                case 429:
                    return [429, __('inbox.error.too_many')];
                case 413:
                    return [413, 'The file is too large. Please upload a smaller file.'];
                default:
                    return [$e->getStatusCode(), __('inbox.error.generic')];
            }
        }

        return [500, __('inbox.error.generic')];
    }
}
