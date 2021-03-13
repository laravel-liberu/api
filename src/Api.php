<?php

namespace LaravelEnso\Api;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use LaravelEnso\Api\Contracts\Endpoint;
use LaravelEnso\Api\Contracts\Retry;
use LaravelEnso\Api\Contracts\Token;
use LaravelEnso\Api\Contracts\UsesAuth;
use LaravelEnso\Api\Enums\Methods;
use LaravelEnso\Api\Enums\ResponseCodes;

abstract class Api
{
    protected Endpoint $endpoint;

    protected int $tries;

    public function __construct(Endpoint $endpoint)
    {
        $this->endpoint = $endpoint;
        $this->tries = 0;
    }

    public function call(): Response
    {
        $this->tries++;

        $response = $this->response();

        if ($response->failed()) {
            if ($this->possibleTokenExpiration($response)) {
                $this->tokenManager()->auth();

                return $this->call();
            }

            if ($this->shouldRetry($response)) {
                return $this->call();
            }
        }

        return $response->throw();
    }

    abstract protected function tokenManager(): Token;

    protected function response(): Response
    {
        $method = Methods::get($this->endpoint->method());

        return Http::withHeaders($this->headers())
            ->withOptions(['debug' => Config::get('enso.frisbo.debug')])
            ->{$method}($this->url(), $this->endpoint->body());
    }

    protected function headers()
    {
        $headers = ['X-Requested-With' => 'XMLHttpRequest'];

        if ($this->endpoint instanceof UsesAuth) {
            $token = $this->tokenManager()->current();
            $headers['Authorization'] = "Bearer {$token}";
        }

        return $headers;
    }

    protected function url(): string
    {
        $baseUrl = Config::get('enso.frisbo.baseUrl');

        return "{$baseUrl}/{$this->endpoint->path()}";
    }

    protected function shouldRetry(): bool
    {
        return $this->endpoint instanceof Retry
            && $this->tries < $this->endpoint->tries();
    }

    protected function possibleTokenExpiration(Response $response): bool
    {
        return $this->endpoint instanceof UsesAuth
            && ResponseCodes::needsAuth($response->status())
            && $this->tries === 1;
    }
}
