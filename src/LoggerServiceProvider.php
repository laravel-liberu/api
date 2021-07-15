<?php

namespace LaravelEnso\Api;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\Api\Http\Middleware\ApiLogger;

class LoggerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['router']->prependMiddlewareToGroup(
            'core-api',
            ApiLogger::class
        );
    }

    public function register()
    {
        $this->app->singleton(ApiLogger::class);
    }
}
