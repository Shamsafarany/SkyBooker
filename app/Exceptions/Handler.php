<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Route not found',
                    'error' => 'The endpoint you are trying to reach does not exist.',
                    'code' => 404,
                    'timestamp' => now()->toISOString(),
                ], 404);
            }
        });

        $this->renderable(function (ModelNotFoundException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource not found',
                    'error' => 'The requested resource does not exist.',
                    'code' => 404,
                    'timestamp' => now()->toISOString(),
                ], 404);
            }
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // ✅ Handle API 404 errors
        if ($request->expectsJson() || $request->is('api/*')) {
            if ($exception instanceof NotFoundHttpException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Route not found',
                    'error' => 'The endpoint you are trying to reach does not exist.',
                    'code' => 404,
                    'timestamp' => now()->toISOString(),
                ], 404);
            }

            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource not found',
                    'error' => 'The requested resource does not exist.',
                    'code' => 404,
                    'timestamp' => now()->toISOString(),
                ], 404);
            }

            // ✅ Handle method not allowed
            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Method not allowed',
                    'error' => 'The HTTP method is not supported for this route.',
                    'allowed_methods' => $exception->getHeaders()['Allow'] ?? [],
                    'code' => 405,
                    'timestamp' => now()->toISOString(),
                ], 405);
            }

            // ✅ Handle authentication errors
            if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated',
                    'error' => 'You need to be authenticated to access this resource.',
                    'code' => 401,
                    'timestamp' => now()->toISOString(),
                ], 401);
            }

            // ✅ Handle authorization errors
            if ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'error' => 'You do not have permission to access this resource.',
                    'code' => 403,
                    'timestamp' => now()->toISOString(),
                ], 403);
            }

            // ✅ Handle validation errors
            if ($exception instanceof \Illuminate\Validation\ValidationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $exception->errors(),
                    'code' => 422,
                    'timestamp' => now()->toISOString(),
                ], 422);
            }

            // ✅ Handle general exceptions (for debugging)
            if (config('app.debug')) {
                return response()->json([
                    'success' => false,
                    'message' => $exception->getMessage(),
                    'error' => 'An unexpected error occurred.',
                    'code' => 500,
                    'timestamp' => now()->toISOString(),
                    'debug' => [
                        'file' => $exception->getFile(),
                        'line' => $exception->getLine(),
                        'trace' => $exception->getTraceAsString(),
                    ]
                ], 500);
            }

            // ✅ Production error
            return response()->json([
                'success' => false,
                'message' => 'Server error',
                'error' => 'An unexpected error occurred. Please try again later.',
                'code' => 500,
                'timestamp' => now()->toISOString(),
            ], 500);
        }

        return parent::render($request, $exception);
    }
}