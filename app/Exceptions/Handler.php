<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong',
                    'data' => [],
                    'errors' => [],
                ], 401);
            }

            return redirect()->guest(
                url()->previous() ?? url()->current()
            );
        });

        $this->reportable(function (Throwable $e) {
            Log::error('Web Exception', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'url'     => request()->fullUrl(),
                'user_id' => auth()->id(),
            ]);
        });

        $this->renderable(function (ThrottleRequestsException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong',
                    'data' => [],
                    'errors' => [],
                ], 429);
            }

            return back()->with(
                'error',
                'Too many attempts. Please wait and try again.'
            );
        });

        $this->renderable(function (
            ModelNotFoundException|NotFoundHttpException $e,
            $request
        ) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong',
                    'data' => [],
                    'errors' => [],
                ], 404);
            }

            return redirect()
                ->to(url()->previous() ?? url()->current())
                ->with('error', 'Requested record not found.');
        });

        $this->renderable(function (ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong',
                    'data' => [],
                    'errors' => $e->errors(),
                ], 422);
            }

            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        });

        $this->renderable(function (Throwable $e, $request) {
            $errorMessage = $e->getMessage();

            if (
                str_contains($errorMessage, 'Duplicate entry') ||
                str_contains($errorMessage, 'already exists') ||
                str_contains($errorMessage, 'unique')
            ) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Something went wrong',
                        'data' => [],
                        'errors' => [],
                    ], 409);
                }

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'This email is already registered. Please login instead.'
                    );
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong',
                    'data' => [],
                    'errors' => [],
                ], 500);
            }

            return redirect()
                ->to(url()->previous() ?? url()->current())
                ->withInput()
                ->with(
                    'error',
                    $errorMessage ?: 'Something went wrong. Please try again.'
                );
        });
    }
}