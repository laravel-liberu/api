<?php

namespace LaravelEnso\Api\Exceptions;

use LaravelEnso\Helpers\Exceptions\EnsoException;

class Api extends EnsoException
{
    public static function disabled(): self
    {
        return new static(__('Api is disabled'));
    }
}
