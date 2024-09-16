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

    public function getByInvoice(string $invoice)
    {
        return Detail::where('dt_invoice', '=', $invoice)->get();
    }

    public function getByInvoiceFromOems(string $invoice){
        return Oems::where('dt_invoice', '=', $invoice)->first();
    }

    public function getSameDetails($id)
    {
        $detail = $this->getByInvoice($id);

        $brand = $detail->pluck('dt_typec')[0];
        if ($brand === 'ГЕНЕРАТОР') {
            $ids = [];
            $generators = AltCz::where('hcparts', '=', $id)->get()->toArray();
            Log::info($generators);
            foreach ($generators as $generator){
                array_push($ids, $generator['dt_code']);
            }
            $ids = array_unique($ids);
            Log::info($ids);
            unset($ids[array_search('', $ids)]);
            $details = Detail::whereIn('dt_cargo', $ids)->orWhereIn('dt_invoice', $ids)->orWhereIn('dt_oem', $ids)->get()->toArray();
            Log::info($details);
            return $details;
        }

        return [];
    }
}
