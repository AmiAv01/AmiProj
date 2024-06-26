<?php

namespace App\Repositories;

use App\Models\Detail;
use App\Repositories\Interfaces\DetailRepositoryInterface;

class DetailRepository implements DetailRepositoryInterface
{
    public function all()
    {
        return Detail::paginate(12);
    }

    public function findById(int $id)
    {
        return Detail::where('dt_id', '=', $id)->get();
    }

    public function findByCategory(array $categories, array $brands)
    {
        return Detail::whereIn('dt_typec', $categories)->whereIn('fr_code', $brands)->paginate(12)->withQueryString();
    }
}
