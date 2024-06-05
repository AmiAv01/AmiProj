<?php

namespace App\Repositories;

use App\Models\Detail;
use App\Repositories\Interfaces\DetailRepositoryInterface;
use Illuminate\Support\Facades\DB;

class DetailRepository implements DetailRepositoryInterface
{
    public function all()
    {
        return DB::table('detail')->paginate(12);
    }

    public function findById(int $id)
    {
        return Detail::findOrFail($id);
    }

    public function findByCategory(array $categories)
    {
        return Detail::where('dt_typec', $categories)->paginate(12);
    }
}
