<?php

namespace App\Services\AdminSearchService;

use App\Services\UserService;

final class AdminUserSearchService implements AdminSearchInterface
{

    public function __construct(protected UserService $userService)
    {}

    public function search(string $searchQ, int $perPageForSearch, int $perPageForAll)
    {
        return ($searchQ) ? $this->userService->getBySearching($searchQ, $perPageForSearch) : $this->userService->getAll($perPageForAll);
    }
}
