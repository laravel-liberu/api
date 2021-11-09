<?php

namespace LaravelEnso\Api\Exceptions;

use Illuminate\Support\Str;
use LaravelEnso\Api\Action;
use LaravelEnso\Helpers\Exceptions\EnsoException;

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
