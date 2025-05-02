<?php

namespace App\Exceptions;

use Exception;

class ImageStorageException extends Exception
{
    public function __construct(string $message = "Image storage operation failed")
    {
        parent::__construct($message, 500);
    }
}
