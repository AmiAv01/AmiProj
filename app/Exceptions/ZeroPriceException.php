<?php

namespace App\Exceptions;

use Exception;

class ZeroPriceException extends Exception
{
    public function __construct(string $message = "Product price must be greater than zero.", int $code = 500)
    {
        parent::__construct($message, $code);
    }
}
