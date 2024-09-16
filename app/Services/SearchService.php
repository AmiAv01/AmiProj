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
            if (str_starts_with( $detail['dt_oem'], $searchQuery )) {
                $data[$i]['dt_code'] = $detail['dt_oem'];
                $data[$i]['dt_firm'] = $detail['fr_code'];
                $data[$i]['dt_typec'] = $detail['dt_typec'];
                $i++;
            } else {
                $data[$i]['dt_code'] = $detail['dt_invoice'];
                $data[$i]['dt_firm'] = $detail['dt_parent'];
                $data[$i]['dt_typec'] = $detail['dt_typec'];
                $i++;
            }
        }
        usort($data, function ($a, $b) {
            return strcmp($a['dt_code'], $b['dt_code']);
        });
        foreach ($data as $item) {
            $result[md5($item['dt_code'].$item['dt_firm'])] = $item;
        }
        return $result;

    }

    public function getBySearchingWithPagination(string $searchQuery)
    {
        $detailsFromOems = Oems::search($searchQuery)->get();
        $ids = array_unique(array_merge($detailsFromOems->pluck('dt_oem')->toArray(), $detailsFromOems->pluck('dt_invoice')->toArray()));

        $details = Detail::whereIn('dt_invoice', $sortIds)->orWhereIn('dt_oem', $sortIds)->select('dt_invoice', 'dt_oem', 'dt_typec', 'fr_code', 'dt_cargo')->paginate(12)->withQueryString();
        return $details;
    }
}

