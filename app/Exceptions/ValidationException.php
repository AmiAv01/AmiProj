<?php

namespace App\Exceptions;

use Exception;

class ValidationException extends AppException
{
    protected $code = 400;

    public function __construct(string $message = 'Validation error')
    {
        parent::__construct($message);
    }
}
