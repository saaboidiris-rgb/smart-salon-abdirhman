<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
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
     *
     * We force HTTPS links whenever the app is running in production so
     * generated URLs (password reset emails, asset() links, etc.) are correct
     * behind a load balancer / reverse proxy.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // We don't ship Tailwind/Bootstrap, so pagination links use our own
        // glass-styled view instead of Laravel's default Tailwind markup.
        Paginator::defaultView('vendor.pagination.custom');
        Paginator::defaultSimpleView('vendor.pagination.custom');
    }
}
