<?php

namespace LaravelEnso\Api\Exceptions;

use Illuminate\Database\Eloquent\Collection;
use LaravelEnso\Api\Notifications\ApiCallError;
use LaravelEnso\Core\Models\User;
use LaravelEnso\Roles\Enums\Roles;
use Throwable;

class Handler
{
    private Throwable $exception;
    private string $action;
    private array $body;

    public function __construct(Throwable $exception, string $action, array $body)
    {
        $this->exception = $exception;
        $this->action = $action;
        $this->body = $body;
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
        return User::active()->whereRoleId(Roles::Admin)->get();
    }
}
