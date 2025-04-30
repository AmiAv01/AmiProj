<?php

namespace App\Factories;

use App\Services\AdminSearchService\AdminSearchInterface;

interface SearchServiceFactoryInterface
{
    public function create(string $category): AdminSearchInterface;
}
