<?php

namespace App\Services\AdminSearchService;

use App\Services\DetailService;
use Illuminate\Pagination\LengthAwarePaginator;

final class AdminDetailsSearchService implements AdminSearchInterface
{

    public function __construct(protected DetailService $detailService)
    {}

    public function search(string $searchQ, int $perPageForSearch, int $perPageForAll):LengthAwarePaginator
    {
        return ($searchQ) ? $this->detailService->getBySearching($searchQ, $perPageForSearch) : $this->detailService->getByBrand($perPageForAll);
    }
}
