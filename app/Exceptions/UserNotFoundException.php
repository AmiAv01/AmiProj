<?php

namespace App\Exceptions;

class UserNotFoundException extends NotFoundException
{
    public function __construct(int $userId)
    {
        parent::__construct("User not found with ID: {$userId}");
    }
}
