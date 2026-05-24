<?php

namespace App\Enums;

enum OrderStatus: string
{
    case NEW = 'Новый';
    case ACCEPTED = 'Принят';
    case DONE = 'Выполнен';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(static fn (self $status): string => $status->value, self::cases());
    }
}
