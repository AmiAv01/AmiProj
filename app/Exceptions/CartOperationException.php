<?php

namespace App\Exceptions;

use Exception;

class CartOperationException extends Exception
{
    public function __construct(string $message, int $code = 400)
    {
        parent::__construct("Cart operation failed: {$message}", $code);
    }
}
