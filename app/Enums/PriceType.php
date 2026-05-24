<?php

namespace App\Enums;

enum PriceType: string
{
    case Opt = 'o';
    case Zakup = 'z';
    case Prod = 'p';

    public function getColumn(): string
    {
        return match ($this) {
            self::Opt => 'opt',
            self::Zakup => 'zakup',
            self::Prod => 'prod',
        };
    }
}
