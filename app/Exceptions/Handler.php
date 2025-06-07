<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{
    // Don't change this if it's already present
    protected $levels = [
        // Customize log levels if needed
    ];

    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    // ✅ HANDLE UNAUTHENTICATED USERS
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof TokenMismatchException) {
            // Allow default behavior during POST to login route
            if ($request->isMethod('post') && $request->is('login')) {
                return parent::render($request, $exception);
            }

            // Redirect only if the user was on a GET route
            if ($request->isMethod('get')) {
                return redirect()->route('login')->with('message', 'Your session has expired. Please log in again.');
            }

            // Otherwise, show a 419 page or return default Laravel behavior
            return response()->view('errors.419', [], 419);
        }

        return parent::render($request, $exception);
    }

}
