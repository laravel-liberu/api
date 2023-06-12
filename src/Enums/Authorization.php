<?php

namespace LaravelEnso\Api\Enums;

use LaravelEnso\Enums\Services\Enum;

class Authorization extends Enum
{
    protected static bool $localisation = false;
    protected static bool $validatesKeys = true;

    final public const Basic = 'Basic';
    final public const Bearer = 'Bearer';
}
