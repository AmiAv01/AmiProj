<?php

namespace App\Services\AdminSearchService;

use App\Services\NewsService;
use Illuminate\Pagination\LengthAwarePaginator;

final class AdminNewsSearchService extends AbstractAdminSearchService
{

    public function __construct(protected NewsService $newsService)
    {}

    protected function searchWithQuery(string $query, int $perPage): LengthAwarePaginator
    {
        return $this->newsService->getBySearching($query, $perPage);
    }

    protected function getAllItems(int $perPage): LengthAwarePaginator
    {
        return $this->newsService->getAll($perPage);
    }
}
