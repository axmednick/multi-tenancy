<?php

namespace App\Providers;

use Illuminate\Console\Events\CommandStarting;
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
        if ($this->app->runningInConsole()) {
            $this->app['events']->listen(CommandStarting::class, function ($event) {
                if ($event->command === 'migrate') {
                    echo "\n⚠️  Use `php artisan migrate:central` for Central module migrations.\n\n";
                    exit; // stop default migrate
                }
            });
        }
    }
}
