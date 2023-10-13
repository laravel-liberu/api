<?php

namespace LaravelLiberu\Api\Enums;

use LaravelLiberu\Enums\Services\Enum;

class Authorization extends Enum
{
    protected static bool $localisation = false;
    protected static bool $validatesKeys = true;

    final public const Basic = 'Basic';
    final public const Bearer = 'Bearer';
}
