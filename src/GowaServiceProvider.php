<?php

namespace NotificationChannels\Gowa;

use Illuminate\Support\ServiceProvider;

class GowaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(GowaApi::class, function () {
            $config = config('services.gowa');

            return new GowaApi($config);
        });
    }

    public function boot()
    {
        // Boot method for Laravel 11+ compatibility
    }
}
