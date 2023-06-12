<?php

namespace LaravelEnso\Api\Enums;

use LaravelEnso\Enums\Services\Enum;

class Methods extends Enum
{
    protected static bool $localisation = false;
    protected static bool $validatesKeys = true;

    final public const get = 'get';
    final public const post = 'post';
    final public const put = 'put';
    final public const delete = 'delete';
}
