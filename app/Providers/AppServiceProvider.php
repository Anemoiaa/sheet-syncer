<?php

namespace App\Providers;

use App\Services\ExternalTableServiceInterface;
use App\Services\GoogleSheetService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            ExternalTableServiceInterface::class,
            GoogleSheetService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
