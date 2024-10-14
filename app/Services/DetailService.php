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
    public function getAll(int $perPage)
    {
        return Detail::paginate($perPage);
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

    public function getByBrand()
    {
        $brands = QueryBuilder::for(Firm::class)->allowedFilters(AllowedFilter::exact('id', 'fr_code'))->get();

        return Detail::whereIn('fr_code', $brands->pluck('fr_name')->toArray())->paginate(12)->withQueryString();
    }

    public function getByInvoice(string $invoice)
    {
        return Detail::where('dt_invoice', '=', $invoice)->get();
    }

    public function getByCodeFromOems(string $code){
        Log::info($code);
        return Oems::where('dt_invoice', '=', $code)->orWhere('dt_oem', '=', $code)->first();
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

    public function getAnalogs($id){
        $detailsFromOems = Oems::where('dt_invoice', '=', $id)->orWhere('dt_oem', '=', $id)->get()->toArray();
        $ids = [];
        $i = 0;
        foreach ($detailsFromOems as $detail) {
            if ($detail['dt_oem'] === $id ) {
                $ids[$i] = $detail['dt_invoice'];
                $i++;
            } elseif($detail['dt_invoice'] === $id) {
                $ids[$i] = $detail['dt_oem'];
                $i++;
            }
        }
        $details = Detail::whereIn('dt_invoice', $ids)->orWhereIn('dt_oem', $ids)->orWhereIn('dt_cargo', $ids)->get()->toArray();
        return $details;
    }

    public function getCargoFromAnalogs(array $details):array{
        $codeIds = [];
        foreach ($details as $detail){
            array_push($codeIds, $detail['dt_cargo']);
            array_push($codeIds, $detail['dt_oem']);
            array_push($codeIds, $detail['dt_invoice']);
        }
        $detailsFromOems = Oems::whereIn('dt_invoice', array_unique($codeIds))
            ->orWhereIn('dt_oem', array_unique($codeIds))->where('fr_code', '=', 'CARGO')
            ->where('dt_parent', '=', 'CARGO')->get();
        $cargoIds = array_unique($detailsFromOems->pluck('dt_invoice')->toArray());
        Log::info($cargoIds);
        return array_values($cargoIds);
    }
}
