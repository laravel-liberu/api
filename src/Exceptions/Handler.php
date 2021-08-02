<?php

namespace LaravelEnso\Api\Exceptions;

use Illuminate\Database\Eloquent\Collection;
use LaravelEnso\Api\Notifications\ApiCallError;
use LaravelEnso\Users\Models\User;

class Handler
{
    public function __construct(
        private string $action,
        private string $url,
        private array $body,
        private int | string $code,
        private string $message
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
