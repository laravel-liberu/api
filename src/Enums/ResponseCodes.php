<?php

namespace LaravelEnso\Api\Enums;

use LaravelEnso\Enums\Services\Enum;

class ResponseCodes extends Enum
{
    public const OK = 200;
    public const Created = 201;

    public const Unauthorized = 401;
    public const Forbidden = 403;

    public const NotFound = 404;
    public const UnprocessableEntity = 422;

    public static function needsAuth(int $code): bool
    {
        return in_array($code, [self::Unauthorized, self::Forbidden]);
    }
}
