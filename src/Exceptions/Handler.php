<?php

namespace LaravelEnso\Api\Exceptions;

use Illuminate\Database\Eloquent\Collection;
use LaravelEnso\Api\Notifications\ApiCallError;
use LaravelEnso\Core\Models\User;
use Throwable;

class Handler
{
    private string $action;
    private array $body;
    private Throwable $exception;

    public function __construct(string $action, array $body, Throwable $exception)
    {
        $this->action = $action;
        $this->body = $body;
        $this->exception = $exception;
    }

    public function report()
    {
        $this->admins()->each->notify($this->notification());
    }

    private function notification(): ApiCallError
    {
        $args = [$this->action, $this->body, $this->exception];

        return (new ApiCallError(...$args))->onQueue('notifications');
    }

    private function admins(): Collection
    {
        return User::active()->admins()->get();
    }
}
