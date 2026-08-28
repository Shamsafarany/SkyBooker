<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Response;


class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        if ($request->is('api/*') || $request->expectsJson()) {

            if ($e instanceof ModelNotFoundException) {
                return Response::error('Record not found', 404);
            }

            if ($e instanceof NotFoundHttpException) {
                return Response::error('Endpoint not found', 404);
            }

            if ($e instanceof MethodNotAllowedHttpException) {
                return Response::error('Method not allowed', 405);
            }
        }

        return parent::render($request, $e);
    }

}