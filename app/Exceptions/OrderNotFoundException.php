<?php

namespace App\Exceptions;

class OrderNotFoundException extends NotFoundException
{
    public function __construct(int $orderId)
    {
        parent::__construct("Order not found with ID: {$orderId}");
    }
}
