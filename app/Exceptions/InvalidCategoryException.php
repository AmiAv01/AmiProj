<?php

namespace App\Exceptions;

use Exception;

class InvalidCategoryException extends \DomainException
{
    public function __construct(string $category)
    {
        parent::__construct("Invalid search category: {$category}");
    }
}
