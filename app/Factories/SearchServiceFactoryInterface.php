<?php

namespace App\Factories;

use App\Enums\Category;
use App\Services\AdminSearchService\AdminSearchInterface;

interface SearchServiceFactoryInterface
{
    public function create(Category $category): AdminSearchInterface;
}
