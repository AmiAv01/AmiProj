<?php

namespace App\Services\AdminSearchService;

use App\Services\OrderService;

final class AdminOrderSearchService implements AdminSearchInterface
{

    public function __construct(protected OrderService $orderService)
    {}

    public function search(string $searchQ, int $perPageForSearch, int $perPageForAll)
    {
        return ($searchQ) ? $this->orderService->getBySearching($searchQ, $perPageForSearch) : $this->orderService->getAll($perPageForAll);
    }
}
