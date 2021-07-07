<?php

namespace LaravelEnso\Api;

use LaravelEnso\Api\Contracts\Endpoint;
use LaravelEnso\Api\Exceptions\Handler;
use Throwable;

abstract class Action
{
    public function handle()
    {
        try {
            $response = (new Api($this->endpoint()))->call();
            $args = [
                static::class,
                $this->endpoint()->url(),
                $this->endpoint()->body(),
                $response->getCode(),
                $response->getMessage(),
            ];

            if ($response->failed()) {
                (new Handler(...$args))->report();
                $this->wasReported = true;
            }

            return $response->throw();
        } catch (Throwable $exception) {
            $args = [
                static::class,
                $this->endpoint()->url(),
                $this->endpoint()->body(),
                $exception->getCode(),
                $exception->getMessage(),
            ];

            (new Handler(...$args))->report();

            throw $exception;
        }
    }

    private function log()
    {
        Log::create([
            'user_id' => Auth::user()?->id,
            'url' => $this->endpoint->url(),
            'route' => Route::currentRouteName(),
            'method' => $this->endpoint->method(),
            'try' => $this->tries,
            'type' => Calls::Outbound,
        ]);
    }

    abstract protected function endpoint(): Endpoint;
}
