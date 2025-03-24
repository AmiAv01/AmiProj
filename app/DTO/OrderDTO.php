<?php

namespace App\DTO;

class OrderDTO
{
    public function __construct(public readonly int $totalPrice, public readonly string $status, public readonly int $userId)
    {}
}
