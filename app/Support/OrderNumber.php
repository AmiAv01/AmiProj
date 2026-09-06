<?php

namespace App\Support;

final class OrderNumber
{
    private const string PREFIX = 'ORD-';

    private const string ALPHABET = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';

    private const int RANDOM_LENGTH = 10;

    public static function generate(): string
    {
        $number = self::PREFIX;
        $lastIndex = strlen(self::ALPHABET) - 1;

        for ($index = 0; $index < self::RANDOM_LENGTH; $index++) {
            $number .= self::ALPHABET[random_int(0, $lastIndex)];
        }

        return $number;
    }
}
