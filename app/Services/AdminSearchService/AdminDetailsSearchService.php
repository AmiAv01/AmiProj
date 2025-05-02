<?php

namespace App\Services\AdminSearchService;

use App\Services\DetailService;
use Illuminate\Pagination\LengthAwarePaginator;

final class AdminDetailsSearchService extends AbstractAdminSearchService
{

    public function __construct(protected DetailService $detailService)
    {}

    protected function searchWithQuery(string $query, int $perPage): LengthAwarePaginator
    {
        return $this->detailService->getBySearching($query, $perPage);
    }

    protected function getAllItems(int $perPage): LengthAwarePaginator
    {
        return $this->detailService->getByBrand($perPage);
    }
}
