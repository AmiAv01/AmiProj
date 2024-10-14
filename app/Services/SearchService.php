<?php

namespace App\Services;

use App\Models\Detail;
use App\Models\Oems;
use Illuminate\Support\Facades\Log;

class SearchService{
    public function getBySearching(string $searchQuery)
    {
        $detailsFromOems = Oems::where('dt_invoice', 'like', "$searchQuery%")->orWhere('dt_oem', 'like', "$searchQuery%")->get()->toArray();
        $data = [];
        $result = [];
        $i = 0;
        foreach ($detailsFromOems as $detail) {
            $data[$i] = $this->getInfoAboutDetailFromOems($detail, $searchQuery);
            $i++;
        }
        usort($data, function ($a, $b) {
            return strcmp($a['dt_code'], $b['dt_code']);
        });
        foreach ($data as $item) {
            $result[md5($item['dt_code'].$item['dt_firm'])] = $item;
        }
        return $result;
    }

    public function getInfoAboutDetailFromOems($detail, $searchQuery){
        $resultData = [];
        if (str_starts_with( $detail['dt_oem'], $searchQuery)) {
            $resultData['dt_code'] = $detail['dt_oem'];
            $resultData['dt_firm'] = $detail['fr_code'];
            $resultData['dt_typec'] = $detail['dt_typec'];
        } else {
            $resultData['dt_code'] = $detail['dt_invoice'];
            $resultData['dt_firm'] = $detail['dt_parent'];
            $resultData['dt_typec'] = $detail['dt_typec'];
        }
        return $resultData;
    }

    public function getBySearchingWithPagination(string $searchQuery)
    {
        $detailsFromOems = Oems::search($searchQuery)->get();
        $ids = array_unique(array_merge($detailsFromOems->pluck('dt_oem')->toArray(), $detailsFromOems->pluck('dt_invoice')->toArray()));

        $details = Detail::whereIn('dt_invoice', $sortIds)->orWhereIn('dt_oem', $sortIds)->select('dt_invoice', 'dt_oem', 'dt_typec', 'fr_code', 'dt_cargo')->paginate(12)->withQueryString();
        return $details;
    }
}

