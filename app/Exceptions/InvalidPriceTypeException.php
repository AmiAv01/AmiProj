<?php

namespace App\Exceptions;

class InvalidPriceTypeException extends ValidationException
{
    public function __construct(string $priceType)
    {
        parent::__construct("Invalid price type: '{$priceType}'. Expected 'o' or 'z'.");
    }
}
