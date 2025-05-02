<?php

namespace App\Services\AdminSearchService;

use App\Services\OrderService;
use Illuminate\Pagination\LengthAwarePaginator;

final class AdminOrderSearchService extends AbstractAdminSearchService
{

    public function __construct(protected OrderService $orderService)
    {}

    protected function searchWithQuery(string $query, int $perPage): LengthAwarePaginator
    {
        return $this->orderService->getBySearching($query, $perPage);
    }

    protected function getAllItems(int $perPage): LengthAwarePaginator
    {
        return $this->orderService->getAll($perPage);
    }
}
