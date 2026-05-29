<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Dynamically point the public path to sibling public_html in cPanel environments
        if (file_exists(dirname(base_path()) . '/public_html')) {
            $this->app->usePublicPath(dirname(base_path()) . '/public_html');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
