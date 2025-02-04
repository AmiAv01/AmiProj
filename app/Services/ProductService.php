<?php

namespace App\Services;

use App\Models\AltCz;
use App\Models\Detail;
use App\Models\Oems;
use App\Models\RozCz;
use Illuminate\Support\Facades\Log;

class ProductService
{

    public function getSameDetails(string $detailType, string $detailInvoice): array
    {
        return match($detailType){
            'ГЕНЕРАТОР' => $this->getSameDetailsForGenerator($detailInvoice),
            'СТАРТЕР' => $this->getSameDetailsForStarters($detailInvoice),
            default => [],
        };
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

    public function getProductInfoFromOems(SearchService $searchService, string $code): array{
        $detailFromOems = Oems::ofCode($code)->first();
        return $searchService->getInfoAboutDetailFromOems($detailFromOems, $code);
    }

    private function getSameDetailsForGenerator(string $detailInvoice):array
    {
        $ids = [];
        $generators = AltCz::where('hcparts', '=', $detailInvoice)->get()->toArray();
        foreach ($generators as $generator){
            if (!str_starts_with($generator['dt_code'], '-')){
                $ids[] = $generator['dt_code'];
            }
        }
        $ids = array_unique($ids);
        unset($ids[array_search('', $ids)]);
        return Detail::whereIn('dt_cargo', $ids)->orWhereIn('dt_invoice', $ids)->orWhereIn('dt_oem', $ids)
            ->select(['dt_invoice', 'dt_typec', 'dt_typec', 'dt_cargo', 'fr_code'])->get()->toArray();
    }

    private function getSameDetailsForStarters(string $detailInvoice):array
    {
        $ids = [];
        $starters = RozCz::where('hcparts', '=', $detailInvoice)->get()->toArray();
        foreach ($starters as $starter){
            if (!str_starts_with($starter['dt_code'], '-')){
                $ids[] = $starter['dt_code'];
            }
        }
        $ids = array_unique($ids);
        unset($ids[array_search('', $ids)]);
        return Detail::whereIn('dt_cargo', $ids)->orWhereIn('dt_invoice', $ids)->orWhereIn('dt_oem', $ids)
            ->select(['dt_invoice', 'dt_typec', 'dt_typec', 'dt_cargo', 'fr_code'])->get()->toArray();
    }

    public function getImageUrl(string $imageName = ''):string {
        Log::info($imageName);
        return ($imageName !== '') ? url('storage/images/' . $this->parseImagePath($imageName) . '.jpg') : url('storage/images/no-photo--lg' . '.png');
    }

    private function parseImagePath(string $imagePath):string{
        if (!str_contains($imagePath, ',')){
            return strtolower($imagePath);
        }
        return strtolower(stristr($imagePath, ',', true));
    }
}
