<?php

namespace App\Exceptions;

class InvalidImagePathException extends ValidationException
{
    public function __construct(string $path)
    {
        parent::__construct("Invalid image path format: {$path}");
    }
}
