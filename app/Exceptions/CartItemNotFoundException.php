<?php

namespace App\Exceptions;

use Exception;

class CartItemNotFoundException extends NotFoundException
{
    public function __construct(int $cartId, int $productId)
    {
        parent::__construct(
            "Product with ID {$productId} not found in cart {$cartId}",
        );
    }
}
