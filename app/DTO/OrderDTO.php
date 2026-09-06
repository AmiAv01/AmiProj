<?php

namespace App\DTO;

use App\Enums\OrderStatus;

final readonly class OrderDTO
{
    public function __construct(
        public OrderStatus $status,
        public int $userId,
        public ?string $comment = null,
    ) {}
}
