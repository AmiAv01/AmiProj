<?php

namespace App\DTO;

final class UserDTO
{
    public function __construct(public readonly int $userId, public readonly string $formula) {}
}
