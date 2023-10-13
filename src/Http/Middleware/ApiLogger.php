<?php

namespace LaravelLiberu\Api\Http\Middleware;

use Closure;
use LaravelLiberu\Api\Enums\Calls;
use LaravelLiberu\Api\Models\Log;
use LaravelLiberu\Helpers\Services\Decimals;

class ApiLogger
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        Log::create([
            'user_id'  => $request->user()?->id,
            'url'      => $request->url(),
            'route'    => $request->route()->getName(),
            'method'   => $request->method(),
            'status'   => $response->status(),
            'type'     => Calls::Inbound,
            'duration' => Decimals::sub(microtime(true), LARAVEL_START),
        ]);
    }
}
