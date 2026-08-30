<?php

namespace App\Exceptions;

class ZeroPriceException extends ValidationException
{
    public function __construct(string $message = 'Product price must be greater than zero.')
    {
        parent::__construct($message);
    }
}
