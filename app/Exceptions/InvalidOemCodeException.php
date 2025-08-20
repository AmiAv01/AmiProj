<?php

namespace App\Exceptions;

class InvalidOemCodeException extends ValidationException
{
    public function __construct()
    {
        parent::__construct('OEM code cannot be empty');
    }
}
