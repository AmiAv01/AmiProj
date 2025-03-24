<?php

namespace App\Services\AdminSearchService;

use App\Services\NewsService;

final class AdminNewsSearchService implements AdminSearchInterface
{

    public function __construct(protected NewsService $newsService)
    {}

    public function search(string $searchQ, int $perPageForSearch, int $perPageForAll)
    {
        return ($searchQ) ? $this->newsService->getBySearching($searchQ, $perPageForSearch) : $this->newsService->getAll($perPageForAll);
    }
}
