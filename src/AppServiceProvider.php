<?php

namespace LaravelEnso\Api;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\Api\Http\Middleware\ApiLogger;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/api.php', 'enso.api');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->app['router']->pushMiddlewareToGroup(
            'core-api',
            ApiLogger::class
        );
    }

    public function register()
    {
        $this->app->singleton(ApiLogger::class);

        // $this->app['router']->pushMiddlewareToGroup(
        //     'core-api',
        //     ApiLogger::class
        // );
    }
}
