<?php

namespace App\Exceptions;

use Exception;

class CartItemAlreadyExistException extends Exception
{
    public function __construct(int $cartId, int $productId)
    {
        parent::__construct(
            "Product with ID {$productId} already exists in cart {$cartId}",
            409
        );
    }
}
