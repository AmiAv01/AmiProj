<?php

namespace App\Services;

use App\Models\AltCz;
use App\Models\Detail;
use App\Models\Oems;
use Illuminate\Support\Facades\Log;

class ProductService
{

    public function getSameDetails(Detail $detail): array
    {
        $brand = $detail->dt_typec;
        Log::info($brand);
        if ($brand === 'ГЕНЕРАТОР') {
            $ids = [];
            $generators = AltCz::where('hcparts', '=', $detail->dt_invoice)->get()->toArray();
            foreach ($generators as $generator){
                array_push($ids, $generator['dt_code']);
            }
            $ids = array_unique($ids);
            unset($ids[array_search('', $ids)]);
            $details =  Detail::whereIn('dt_cargo', $ids)->orWhereIn('dt_invoice', $ids)->orWhereIn('dt_oem', $ids)
                ->select(['dt_invoice', 'dt_typec', 'dt_typec', 'dt_cargo', 'fr_code'])->get()->toArray();
            return $details;
        }
        return [];
    }

    public function getAnalogs($id):array
    {
        $detailsFromOems = Oems::ofCode($id)->get()->toArray();
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
        return Detail::whereIn('dt_invoice', $ids)->orWhereIn('dt_oem', $ids)->orWhereIn('dt_cargo', $ids)->get()->toArray();
    }

    public function getCargoFromAnalogs(array $details):array
    {
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
        return array_values($cargoIds);
    }

    public function getProductInfoFromOems(SearchService $searchService, string $code){
        $detailFromOems = Oems::ofCode($code)->first();
        return $searchService->getInfoAboutDetailFromOems($detailFromOems, $code);
    }
}
