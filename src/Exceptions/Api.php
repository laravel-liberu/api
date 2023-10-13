<?php

namespace LaravelLiberu\Api\Exceptions;

use Illuminate\Support\Str;
use LaravelLiberu\Api\Action;
use LaravelLiberu\Helpers\Exceptions\EnsoException;

class Api extends EnsoException
{
    public static function disabled(Action $action): self
    {
        $api = Str::of($action::class)
            ->explode('\\')
            ->splice(1, 1)
            ->first();

        return new static(__(':api API is disabled', [
            'api' => $api,
        ]));
    }
}
