<?php

namespace App\Exceptions;

use Exception;

class DetailNotFoundException extends NotFoundException
{
    public function __construct(string $value)
    {
        parent::__construct("Detail not found with code: {$value}");
    }
}
