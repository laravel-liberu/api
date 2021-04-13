<?php

namespace LaravelEnso\Api;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use LaravelEnso\Api\Contracts\AttachesFiles;
use LaravelEnso\Api\Contracts\Endpoint;
use LaravelEnso\Api\Contracts\Retry;
use LaravelEnso\Api\Contracts\UsesAuth;
use LaravelEnso\Api\Enums\Authorization;
use LaravelEnso\Api\Enums\Methods;
use LaravelEnso\Api\Enums\ResponseCodes;

class Api
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
                $this->endpoint->tokenProvider()->auth();

                return $this->call();
            }

            if ($this->shouldRetry($response)) {
                sleep($this->endpoint->delay());

                return $this->call();
            }
        }

        return $response->throw();
    }

    protected function response(): Response
    {
        $method = Methods::get($this->endpoint->method());

        $http = Http::withHeaders($this->headers());
        //TODO add option in endpoint or action for debug
        if ($this->endpoint instanceof AttachesFiles) {
            $this->endpoint->attach($http);
        }

        return $http->withOptions(['debug' => false])
            ->{$method}($this->endpoint->url(), $this->endpoint->body());
    }

    protected function headers()
    {
        $headers = ['X-Requested-With' => 'XMLHttpRequest'];

        if ($this->endpoint instanceof UsesAuth) {
            $token = $this->endpoint->tokenProvider()->current();
            $type = Authorization::get($this->endpoint->tokenProvider()->type());
            $headers['Authorization'] = "{$type} {$token}";
        }

        return $headers;
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
