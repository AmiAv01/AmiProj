<?php

namespace App\DTO;

class CartDTO
{
    public function __construct(public readonly int $productId, public readonly int $quantity )
    {
    }
}
