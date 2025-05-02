<?php

namespace App\Services;
use App\DTO\OemInfoDTO;
use App\Exceptions\InvalidOemCodeException;
use App\Exceptions\OemNotFoundException;
use App\Models\Oems;
use App\Services\SearchService;
use Ramsey\Collection\Collection;

final class OemService
{
    public function getProductInfoFromOems(string $code): OemInfoDTO{
        if (empty($code)){
            throw new InvalidOemCodeException();
        }
        $detailFromOems = Oems::ofCode($code)->firstOrFail();
        if (empty($detailFromOems)){
            throw new OemNotFoundException($code);
        }
        return $this->getInfoAboutDetailFromOems($detailFromOems, $code);
    }

    public function getInfoAboutDetailFromOems(array | Oems $detail,string $searchQuery): OemInfoDTO
    {
        $isStartWithOem = str_starts_with( $detail['dt_oem'], $searchQuery);
        return new OemInfoDTO(
            code: ($isStartWithOem) ? $detail['dt_oem'] : $detail['dt_invoice'],
            firm:  ($isStartWithOem) ? $detail['fr_code'] : $detail['dt_parent'],
            type:  $detail['dt_typec']
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\Oems[]
     */
    public function findDetailsByQuery(string $searchQuery){
        return Oems::where('dt_invoice', 'like', "$searchQuery%")->orWhere('dt_oem', 'like', "$searchQuery%")->get();
    }
}
