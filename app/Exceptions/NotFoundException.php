<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends AppException
{
    protected $code = 404;

    public function __construct(string $message = 'Resource not found')
    {
        parent::__construct($message);
    }
}
