<?php

namespace LaravelEnso\Api;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\Api\Http\Middleware\ApiLogger;

class LogServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['router']
            ->prependMiddlewareToGroup('core-api', ApiLogger::class);
    }
}
