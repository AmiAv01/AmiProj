<?php

namespace App\Exceptions;

use Exception;

class OemNotFoundException extends Exception
{
    public function __construct(string $code)
    {
        parent::__construct("OEM detail not found for code: {$code}");
    }
}
