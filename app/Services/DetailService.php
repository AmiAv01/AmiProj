<?php

namespace App\Services;

use App\Models\Detail;
use App\Models\Firm;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DetailService
{
    public function getAll()
    {
        return Detail::paginate(12);
    }

    public function getById(int $id)
    {
        return Detail::where('dt_id', '=', $id)->get();
    }

    public function getByCategory(array $categories)
    {
        $brands = QueryBuilder::for(Firm::class)->allowedFilters(AllowedFilter::exact('id', 'fr_code'))->get();

        return Detail::whereIn('dt_typec', $categories)->whereIn('fr_code', $brands->pluck('fr_name')->toArray())->paginate(12)->withQueryString();
    }

    public function getByBrand()
    {
        $brands = QueryBuilder::for(Firm::class)->allowedFilters(AllowedFilter::exact('id', 'fr_code'))->get();

        return Detail::whereIn('fr_code', $brands->pluck('fr_name')->toArray())->paginate(12)->withQueryString();
    }

    public function getBySearching(string $searching)
    {
        $brands = QueryBuilder::for(Firm::class)->allowedFilters(AllowedFilter::exact('id', 'fr_code'))->get();

        return Detail::where('dt_invoice', 'like', "%$searching%")->orWhere('dt_cargo', 'like', "%$searching%")->whereIn('fr_code', $brands->pluck('fr_name')->toArray())->paginate(12)->withQueryString();
    }

    public function getSameDetails($id)
    {
        $detail = $this->getById($id);
        $code = $detail->pluck('dt_cargo')[0];

        return Detail::where('dt_cargo', '=', $code)->where('dt_id', '!=', $id)->get();
    }
}
