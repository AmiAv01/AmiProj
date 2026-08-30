<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class AppException extends HttpException
{
    protected const int STATUS_CODE = 500;

    public function __construct(string $message = 'Application error', ?\Throwable $previous = null)
    {
        parent::__construct(static::STATUS_CODE, $message, $previous);
    }
}
