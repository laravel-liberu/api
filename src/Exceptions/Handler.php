<?php

namespace LaravelLiberu\Api\Exceptions;

use Illuminate\Database\Eloquent\Collection;
use LaravelLiberu\Api\Notifications\ApiCallError;
use LaravelLiberu\Users\Models\User;

class Handler
{
    public function __construct(
        private readonly string $action,
        private readonly string $url,
        private readonly string|array $body,
        private readonly int|string $code,
        private readonly string $message
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
            $this->action, $this->url, $this->body, $this->code, $this->message,
        ];

        return (new ApiCallError(...$args))->onQueue('notifications');
    }

    private function admins(): Collection
    {
        return User::active()->admins()->get();
    }
}
