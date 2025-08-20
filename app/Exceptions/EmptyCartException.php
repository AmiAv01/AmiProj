<?php

namespace App\Exceptions;

class EmptyCartException extends ValidationException
{
    public function __construct()
    {
        parent::__construct('Cannot create order: cart is empty');
    }
}
