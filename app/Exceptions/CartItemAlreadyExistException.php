<?php

namespace App\Exceptions;

class CartItemAlreadyExistException extends AppException
{
    protected const int STATUS_CODE = 409;

    public function __construct(int $cartId, int $productId)
    {
        parent::__construct("Product with ID {$productId} already exists in cart {$cartId}");
    }
}
