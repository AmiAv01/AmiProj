<?php

namespace App\DTO;

readonly class CartDTO
{
    public function __construct(public int $productId, public int $quantity, public string $productPrice) {}
}
