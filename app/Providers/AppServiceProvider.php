<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('success', function ($data, $message = 'OK') {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ]);
    });

    Response::macro('error', function ($message, $code = 400) {
        return response()->json([
            'status' => 'error',
            'message' => $message
        ], $code);
    });
    }
}
