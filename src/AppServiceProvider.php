<?php

namespace LaravelLiberu\Api;

use Illuminate\Support\ServiceProvider;
use LaravelLiberu\Api\Http\Middleware\ApiLogger;
use LaravelLiberu\Core\Http\Middleware\VerifyActiveState;
use LaravelLiberu\Localisation\Http\Middleware\SetLanguage;
use LaravelLiberu\Permissions\Http\Middleware\VerifyRouteAccess;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/api.php', 'liberu.api');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    public function register()
    {
        $this->app['router']->aliasMiddleware('api-action-logger', ApiLogger::class);

        $this->app['router']->middlewareGroup('core-api', [
            VerifyActiveState::class,
            ApiLogger::class,
            VerifyRouteAccess::class,
            SetLanguage::class,
        ]);
    }
}
