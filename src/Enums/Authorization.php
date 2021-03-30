<?php

namespace LaravelEnso\Api\Enums;

use LaravelEnso\Enums\Services\Enum;

class Authorization extends Enum
{
    protected static bool $localisation = false;
    protected static bool $validatesKeys = true;

    public const Basic = 'Basic';
    public const Bearer = 'Bearer';
}
