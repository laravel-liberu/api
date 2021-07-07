<?php

namespace LaravelEnso\Api\Http\Middleware;

use Closure;
use LaravelEnso\Api\Enums\Calls;
use LaravelEnso\Api\Models\Log;

class ApiLogger
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        Log::create([
            'user_id' => $request->user()?->id,
            'url' => $request->url(),
            'route' => $request->route()->getName(),
            'method' => $request->method(),
            'try' => 1,
            'status' => $response->status(),
            'type' => Calls::Inbound,
        ]);
    }
}
