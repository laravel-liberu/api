<?php

namespace LaravelEnso\Api\Exceptions;

use InvalidArgumentException;

class Filters extends InvalidArgumentException
{
    public static function invalid(string $filters)
    {
        return new static(__('Invalid filter(s) ":filters"', [
            'filters' => $filters,
        ]));
    }
}
