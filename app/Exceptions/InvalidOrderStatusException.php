<?php

namespace App\Exceptions;

class InvalidOrderStatusException extends ValidationException
{
    public function __construct(string $status)
    {
        parent::__construct("Invalid order status: '{$status}'");
    }
}
