<?php

namespace App\DTO;

class OrderDTO
{
    public function __construct(
        public readonly string $status,
        public readonly int $userId,
        public readonly ?string $comment = null,
    ) {}
}
