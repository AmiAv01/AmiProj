<?php

namespace App\Exceptions;

use Exception;

class InvalidOemCodeException extends ValidationException
{
    public function __construct()
    {
        parent::__construct('OEM code cannot be empty');
    }
}
