<?php

namespace App\Services;

use App\Models\AltCz;
use App\Models\Detail;
use App\Models\Firm;
use App\Models\Oems;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
final class DetailService
{
    public function getAll(int $perPage): LengthAwarePaginator
    {
        return Detail::paginate($perPage);
    }

    public function getByFilters(array $categories, int $perPage): LengthAwarePaginator
    {
        $brands = QueryBuilder::for(Firm::class)->allowedFilters(AllowedFilter::exact('id', 'fr_code'))->get();
        return Detail::whereIn('dt_typec', $categories)->whereIn('fr_code', $brands->pluck('fr_name')->toArray())->paginate($perPage)->withQueryString();
    }

    public function getByBrand(int $perPage): LengthAwarePaginator
    {
        $brands = QueryBuilder::for(Firm::class)->allowedFilters(AllowedFilter::exact('id', 'fr_code'))->get();

        return Detail::whereIn('fr_code', $brands->pluck('fr_name')->toArray())
            ->join('stk', 'stk.code', '=', 'detail.dt_code' )->paginate($perPage)->withQueryString();
    }

    public function getByInvoice(string $invoice): Collection
    {
        return Detail::invoice($invoice)->join('stk', 'stk.code', '=', 'detail.dt_code' )->get();
    }


    public function getBySearching(mixed $search)
    {
    }

}
