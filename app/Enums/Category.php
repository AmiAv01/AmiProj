<?php

namespace App\Enums;


enum Category: string
{
    case DETAILS = 'detail';
    case ORDERS = 'order';
    case NEWS = 'news';
    case USERS = 'user';

    public static function isValid(string $value): bool
    {
        return !is_null(self::tryFrom($value));
    }
}
