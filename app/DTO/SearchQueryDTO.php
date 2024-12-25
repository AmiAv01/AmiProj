<?php

namespace App\DTO;

final class SearchQueryDTO
{
    public function __construct(public readonly string $searchQuery)
    {}
}
