<?php

namespace LaravelLiberu\Api\Enums;

use LaravelLiberu\Enums\Services\Enum;

class ResponseCodes extends Enum
{
    final public const OK = 200;
    final public const Created = 201;

    final public const Unauthorized = 401;
    final public const Forbidden = 403;

    final public const NotFound = 404;
    final public const UnprocessableEntity = 422;

    public static function needsAuth(int $code): bool
    {
        return in_array($code, [self::Unauthorized, self::Forbidden]);
    }
}
