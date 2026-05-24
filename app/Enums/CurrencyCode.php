<?php

namespace App\Enums;

enum CurrencyCode: string
{
    case EUR = 'EUR';
    case USD = 'USD';
    case RUB = 'RUB';

    public function label(): string
    {
        return __("currency." . strtolower($this->value));
    }
}
