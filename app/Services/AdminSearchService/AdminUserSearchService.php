<?php

namespace App\Services\AdminSearchService;

use App\Services\UserService;
use Illuminate\Pagination\LengthAwarePaginator;

final class AdminUserSearchService extends AbstractAdminSearchService
{

    public function __construct(protected UserService $userService)
    {}

    protected function searchWithQuery(string $query, int $perPage): LengthAwarePaginator
    {
        return $this->detailService->getBySearching($query, $perPage);
    }

    protected function getAllItems(int $perPage): LengthAwarePaginator
    {
        return $this->detailService->getAll($perPage);
    }
}
