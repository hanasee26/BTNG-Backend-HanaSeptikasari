<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        // Mengizinkan akses publik ke dokumentasi API Scramble di /docs/api
        Gate::define('viewApiDocs', function ($user = null) {
            return true;
        });
    }
}
