<?php

namespace LaravelEnso\Api\Enums;

use LaravelEnso\Enums\Services\Enum;

class Methods extends Enum
{
    protected static bool $localisation = false;
    protected static bool $validatesKeys = true;

    public const get = 'get';
    public const post = 'post';
    public const put = 'put';
    public const delete = 'delete';
}
