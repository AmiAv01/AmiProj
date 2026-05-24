<?php

namespace App\Exceptions;

class InvalidCategoryException extends \DomainException
{
    public function __construct(string $category)
    {
        parent::__construct("Invalid search category: {$category}");
    }
}
