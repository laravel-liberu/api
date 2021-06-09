<?php

namespace LaravelEnso\Api\Exceptions;

use Illuminate\Database\Eloquent\Collection;
use LaravelEnso\Api\Notifications\ApiCallError;
use LaravelEnso\Users\Models\User;
use Throwable;

class Handler
{
    public function __construct(
        private string $action,
        private string $url,
        private array $body,
        private Throwable $exception
    ) {
    }

    public function report(): void
    {
        $notification = $this->notification();

        $this->admins()->each->notify($notification);
    }

    private function notification(): ApiCallError
    {
        $args = [
            $this->action, $this->url, $this->body,
            $this->exception->getCode(), $this->exception->getMessage(),
        ];

        return (new ApiCallError(...$args))->onQueue('notifications');
    }

    private function admins(): Collection
    {
        return User::active()->admins()->get();
    }
}
