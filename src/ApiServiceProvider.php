<?php

namespace LaravelEnso\Api;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->app->singleton(Api::class, fn ($app, $params) => new Api(...$params));
    }

    public function provides()
    {
        return [Api::class];
    }
}
