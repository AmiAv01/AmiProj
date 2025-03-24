<?php

namespace App\Services\AdminSearchService;

interface AdminSearchInterface
{
    public function search(string $searchQ, int $perPageForSearch, int $perPageForAll);
}
