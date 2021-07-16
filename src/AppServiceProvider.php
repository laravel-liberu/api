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

        $this->app['router']->aliasMiddleware('api-action-logger', ApiLogger::class);
    }
}
