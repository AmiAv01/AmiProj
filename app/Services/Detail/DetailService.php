<?php

namespace App\Services\Detail;

use App\Models\Firm;
use App\Repositories\Interfaces\DetailRepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

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
        $brands = QueryBuilder::for(Firm::class)->allowedFilters(AllowedFilter::exact('id', 'fr_code'))->get();

        return $this->detailRepository->findByCategory($categories, $brands->pluck('fr_name')->toArray());
    }
}
