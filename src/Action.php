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
            return (new Api($this->endpoint()))->call();
        } catch (Throwable $exception) {
            $url = $this->endpoint()->url();
            $body = $this->endpoint()->body();

            (new Handler(static::class, $url, $body, $exception))->report();

            throw $exception;
        }
    }

    abstract protected function endpoint(): Endpoint;
}
