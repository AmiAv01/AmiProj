<?php

namespace App\Services;

use App\DTO\SearchQueryDTO;
use App\Models\Oems;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

final class SearchService{
    public function getBySearching(SearchQueryDTO $dto): array
    {
        $detailsFromOems = Oems::where('dt_invoice', 'like', "$dto->searchQuery%")->orWhere('dt_oem', 'like', "$dto->searchQuery%")->get()->toArray();
        $data = [];
        $result = [];
        $i = 0;
        foreach ($detailsFromOems as $detail) {
            $data[$i] = $this->getInfoAboutDetailFromOems($detail, $dto->searchQuery);
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

    public function getInfoAboutDetailFromOems(array | Oems $detail,string $searchQuery): array
    {
        $resultData = [];
        $isStartWithOem = str_starts_with( $detail['dt_oem'], $searchQuery);
        $resultData['dt_code'] = ($isStartWithOem) ? $detail['dt_oem'] : $detail['dt_invoice'];
        $resultData['dt_firm'] = ($isStartWithOem) ? $detail['fr_code'] : $detail['dt_parent'];
        $resultData['dt_typec'] = $detail['dt_typec'];
        return $resultData;
    }

    public function getBySearchingWithPagination(SearchQueryDTO $dto): LengthAwarePaginator
    {
        $details = $this->getBySearching($dto);
        return new LengthAwarePaginator($details, count($details), 10);
    }
}

