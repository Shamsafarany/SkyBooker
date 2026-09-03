<?php

namespace App\Providers;

use App\Contracts\PDFInterface;
use Illuminate\Support\ServiceProvider;



use App\Services\PdfService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PDFInterface::class, PdfService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
    }
}
