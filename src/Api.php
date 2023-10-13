<?php

namespace LaravelLiberu\Api;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use LaravelLiberu\Api\Contracts\AttachesFiles;
use LaravelLiberu\Api\Contracts\CustomHeaders;
use LaravelLiberu\Api\Contracts\Endpoint;
use LaravelLiberu\Api\Contracts\QueryParameters;
use LaravelLiberu\Api\Contracts\Retry;
use LaravelLiberu\Api\Contracts\UsesAuth;
use LaravelLiberu\Api\Enums\Authorization;
use LaravelLiberu\Api\Enums\Methods;
use LaravelLiberu\Api\Enums\ResponseCodes;

class Api
{
    protected int $tries;
    protected string $method;

    public function __construct(protected Endpoint $endpoint)
    {
        $this->tries = 0;
        $this->method = $endpoint->method();
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

            if ($this->shouldRetry()) {
                sleep($this->endpoint->delay());

                return $this->call();
            }
        }

        return $response;
    }

    public function tries(): int
    {
        return $this->tries;
    }

    protected function response(): Response
    {
        $http = Http::withHeaders($this->headers());

        if ($this->endpoint instanceof AttachesFiles) {
            $this->endpoint->attach($http);
        }

        return $http->withOptions(['debug' => Config::get('liberu.api.debug')])
            ->{$this->method}($this->url(), $this->body());
    }

    protected function headers(): array
    {
        $headers = ['X-Requested-With' => 'XMLHttpRequest'];

        if ($this->endpoint instanceof CustomHeaders) {
            $headers += $this->endpoint->headers();
        }

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

    protected function body(): string|array|null
    {
        if ($this->method === Methods::post) {
            return $this->endpoint->body();
        }

        return $this->endpoint->body() ?: null;
    }

    protected function url(): string
    {
        if ($this->endpoint instanceof QueryParameters) {
            $params = Arr::query($this->endpoint->parameters());

            return Str::of($this->endpoint->url())
                ->when($params, fn ($path) => $path->append("?{$params}"));
        }

        return $this->endpoint->url();
    }
}
