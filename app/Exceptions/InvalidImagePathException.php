<?php

namespace App\Exceptions;

use Exception;

class InvalidImagePathException extends Exception
{
    public function __construct(string $path)
    {
        parent::__construct("Invalid image path format: {$path}");
    }
}
