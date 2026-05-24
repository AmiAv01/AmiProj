<?php

namespace App\Exceptions;

class NoResultsFoundException extends NotFoundException
{
    public function __construct(string $query)
    {
        parent::__construct("No results found for query: {$query}");
    }
}
