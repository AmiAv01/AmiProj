<?php

namespace App\Exceptions;

class ValidationException extends AppException
{
    protected const int STATUS_CODE = 422;

    public function __construct(string $message = 'Validation error')
    {
        parent::__construct($message);
    }
}
