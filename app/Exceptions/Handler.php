<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Response;


class Handler extends ExceptionHandler
{
    public function register(): void
    {
        $this->renderable(function(NotFoundHttpException $e, $request){
            if($request->is('api/*')){
                return Response::error('Resource Not Found', 404);
            }
        });
    }
}