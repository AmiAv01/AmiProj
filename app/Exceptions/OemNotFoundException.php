<?php

namespace App\Exceptions;

class OemNotFoundException extends NotFoundException
{
    public function __construct(string $code)
    {
        parent::__construct("OEM detail not found for code: {$code}");
    }
}
