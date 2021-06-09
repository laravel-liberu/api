<?php

namespace LaravelEnso\Api;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/api.php', 'enso.api');
    }
}
