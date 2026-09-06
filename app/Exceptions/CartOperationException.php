<?php

namespace App\Exceptions;

class CartOperationException extends AppException
{
    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct('Unable to complete the cart operation.', $previous);
    }
}
