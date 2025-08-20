<?php

namespace App\Exceptions;

class CurrencyNotFoundException extends NotFoundException
{
    public function __construct(string $currencyCode)
    {
        parent::__construct("Currency not found with code: {$currencyCode}");
    }
}
