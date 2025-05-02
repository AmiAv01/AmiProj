<?php

namespace App\Exceptions;

use Exception;

class OrderNotFoundException extends NotFoundException
{
    public function __construct(int $orderId)
    {
        parent::__construct("Order not found with ID: {$orderId}");
    }
}
