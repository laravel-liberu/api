<?php

namespace LaravelEnso\Api;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use LaravelEnso\Api\Contracts\Endpoint;
use LaravelEnso\Api\Enums\Calls;
use LaravelEnso\Api\Exceptions\Handler;
use LaravelEnso\Api\Models\Log;
use Throwable;

abstract class Action
{
    private Api $api;
    private bool $handledFailure = false;

    public function handle()
    {
        try {
            $this->api = App::make(Api::class, ['endpoint' => $this->endpoint()]);

            $response = $this->api->call();

            $this->log($response);

            if ($response->failed()) {
                (new Handler(...$this->args($response)))->report();
                $this->handledFailure = true;
            }

            return $response->throw();
        } catch (Throwable $exception) {
            if (! $this->handledFailure) {
                (new Handler(...$this->args($exception)))->report();
            }

            throw $exception;
        }
    }

    abstract protected function endpoint(): Endpoint;

    private function args(Throwable | Response $thrown)
    {
        $args = [
            static::class,
            $this->endpoint()->url(),
            $this->endpoint()->body(),
        ];

        return $thrown instanceof Response
            ? array_merge($args, [$thrown->status(), $thrown->body()])
            : array_merge($args, [$thrown->getCode(), $thrown->getMessage()]);
    }

    private function log(Response $response)
    {
        Log::create([
            'user_id' => Auth::user()?->id,
            'url' => $this->endpoint()->url(),
            'route' => Route::currentRouteName(),
            'method' => $this->endpoint()->method(),
            'status' => $response->status(),
            'try' => $this->api->tries(),
            'type' => Calls::Outbound,
        ]);
    }
}
