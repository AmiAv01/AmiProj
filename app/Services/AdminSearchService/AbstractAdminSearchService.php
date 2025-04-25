<?php

namespace App\Services\AdminSearchService;

use Illuminate\Pagination\LengthAwarePaginator;

abstract class AbstractAdminSearchService implements AdminSearchInterface
{
    public function search(string $searchQ, int $perPageForSearch, int $perPageForAll): LengthAwarePaginator
    {
        return $searchQ ? $this->searchWithQuery($searchQ, $perPageForSearch) : $this->getAllItems($perPageForAll);
    }

    abstract protected function searchWithQuery(string $query, int $perPage): LengthAwarePaginator;
    abstract protected function getAllItems(int $perPage): LengthAwarePaginator;

}
