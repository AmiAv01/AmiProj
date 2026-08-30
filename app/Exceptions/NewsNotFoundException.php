<?php

namespace App\Exceptions;

class NewsNotFoundException extends NotFoundException
{
    public function __construct(string $code)
    {
        parent::__construct("News post not found with ID: {$code}");
    }
}
