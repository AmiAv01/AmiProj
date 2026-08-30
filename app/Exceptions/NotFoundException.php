<?php

namespace App\Exceptions;

class NotFoundException extends AppException
{
    protected const int STATUS_CODE = 404;

    public function __construct(string $message = 'Resource not found')
    {
        parent::__construct($message);
    }
}
