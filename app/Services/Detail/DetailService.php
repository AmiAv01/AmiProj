<?php

namespace App\Services\Detail;

use App\Repositories\Interfaces\DetailRepositoryInterface;

class DetailService
{
    public function __construct(
        protected DetailRepositoryInterface $detailRepository
    ) {
    }

    public function getAll()
    {
        return $this->detailRepository->all();
    }

    public function getById(int $id)
    {
        return $this->detailRepository->findById($id);
    }

    public function getByCategory(array $categories)
    {
        return $this->detailRepository->findByCategory($categories);
    }
}
