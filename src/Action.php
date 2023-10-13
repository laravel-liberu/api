<?php

namespace LaravelLiberu\Api;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use LaravelLiberu\Api\Contracts\Endpoint;
use LaravelLiberu\Api\Enums\Calls;
use LaravelLiberu\Api\Exceptions\Api as Exception;
use LaravelLiberu\Api\Exceptions\Handler;
use LaravelLiberu\Api\Models\Log;
use LaravelLiberu\Helpers\Services\Decimals;
use Throwable;

abstract class Action
{
    private Api $api;
    private bool $handledFailure = false;

    //TODO add return type: Response
    public function handle()
    {
        if (! $this->apiEnabled()) {
            throw Exception::disabled($this);
        }

        try {
            $this->api = App::make(Api::class, ['endpoint' => $this->endpoint()]);

            $timer = microtime(true);

            $response = $this->api->call();

            $duration = Decimals::sub(microtime(true), $timer);

            $this->log($response, $duration);

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

    protected function apiEnabled(): bool
    {
        return true;
    }

    abstract protected function endpoint(): Endpoint;

    private function log(Response $response, string $duration): void
    {
        Log::create([
            'user_id'  => Auth::user()?->id,
            'url'      => $this->endpoint()->url(),
            'route'    => Route::currentRouteName(),
            'method'   => $this->endpoint()->method(),
            'status'   => $response->status(),
            'try'      => $this->api->tries(),
            'type'     => Calls::Outbound,
            'duration' => $duration,
        ]);
    }

    private function args(Throwable|Response $response): array
    {
        $base = [
            static::class, $this->endpoint()->url(), $this->endpoint()->body(),
        ];

        $extra = $response instanceof Response
            ? [$response->status(), $response->body()]
            : [$response->getCode(), $response->getMessage()];

        return [...$base, ...$extra];
    }
}
