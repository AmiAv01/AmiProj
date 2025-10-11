<?php

namespace App\DTO;

use App\Models\Cart;

readonly class CartDTO
{
    public function __construct(
        public readonly int $productId,
        public readonly int $quantity,
        public readonly Cart $cart
    ) {}
}
