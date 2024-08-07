<?php

namespace App\Services;

use App\Models\Detail;
use App\Models\Firm;
use App\Models\Oems;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
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

    public function getByFilters(array $categories)
    {
        $brands = QueryBuilder::for(Firm::class)->allowedFilters(AllowedFilter::exact('id', 'fr_code'))->get();

        return Detail::whereIn('dt_typec', $categories)->whereIn('fr_code', $brands->pluck('fr_name')->toArray())->paginate(12)->withQueryString();
    }

    public function getByBrand(Collection $details)
    {
        $brands = QueryBuilder::for(Firm::class)->allowedFilters(AllowedFilter::exact('id', 'fr_code'))->get();

        return $details->whereIn('fr_code', $brands->pluck('fr_name')->toArray())->withQueryString();
    }

    public function getBySearching(string $searchQuery)
    {
        $detailsFromOems = Oems::search($searchQuery)->get();
        $ids = array_merge($detailsFromOems->pluck('dt_oem')->toArray(), $detailsFromOems->pluck('dt_invoice')->toArray());
        $tmpArr = array_unique($ids);
        $i = 0;
        while ($tmpArr != []) {
            $oems = Oems::whereIn('dt_invoice', $tmpArr)->orWhereIn('dt_oem', $tmpArr)->get();
            $tmpArr = array_unique(array_merge($oems->pluck('dt_oem')->toArray(), $oems->pluck('dt_invoice')->toArray()));
            $tmpArr = array_diff($tmpArr, $ids);
            $ids = array_unique(array_merge($ids, $tmpArr));
            $i++;
        }
        $details = Detail::whereIn('dt_invoice', $ids)->orWhereIn('dt_cargo', $ids)->orWhereIn('dt_oem', $ids)->select('dt_invoice', 'dt_oem', 'dt_typec', 'fr_code', 'dt_foto')->get();
        foreach ($ids as $id) {
            $item = Detail::where('dt_comment', 'like', "%$id%")->select('dt_invoice', 'dt_oem', 'dt_typec', 'fr_code', 'dt_foto')->get();
            Log::info($item);
            if (! $item->isEmpty()){ //&& array_search($item->pluck('dt_invoice'), $ids)) {
                $details->push($item[0]);
            }

        }
        //$details = Detail::whereIn('dt_invoice', $ids)->orWhereIn('dt_oem', $ids)->select('dt_invoice', 'dt_oem', 'dt_typec', 'fr_code')->get();

        return $details;

    }

    public function getBySearchingWithPagination(string $searchQuery)
    {
        $details = Oems::where('dt_invoice', 'like', "%$searchQuery%")->orWhere('dt_typec', 'like', "%$searchQuery%")->orWhere('dt_oem', 'like', "%$searchQuery%")->select('dt_invoice', 'dt_oem', 'dt_typec', 'fr_code');
        //$details = Oems::scopeSearch($searchQuery, []);
        $detailsFromOems = Detail::where('dt_invoice', 'like', "%$searchQuery%")->orWhere('dt_oem', 'like', "%$searchQuery%")->orWhere('dt_typec', 'like', "%$searchQuery%")->select('dt_invoice', 'dt_oem', 'dt_typec', 'fr_code')->union($details)->paginate(12)->withQueryString();

        return $detailsFromOems;
    }

    public function getByInvoice(string $invoice)
    {
        return Detail::where('dt_invoice', '=', $invoice)->get();
    }

    public function getSameDetails($id)
    {
        $detail = $this->getByInvoice($id);
        $code = $detail->pluck('dt_cargo')[0];

        return Detail::where('dt_cargo', '=', $code)->where('dt_id', '!=', $id)->get();
    }
}
