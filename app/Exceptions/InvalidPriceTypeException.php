<?php

namespace App\Exceptions;

use Exception;

class InvalidPriceTypeException extends ValidationException
{
    public function __construct(string $priceType)
    {
        parent::__construct("Invalid price type: '{$priceType}'. Expected 'o' or 'z'.");
    }
}
