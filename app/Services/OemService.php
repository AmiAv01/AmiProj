<?php

namespace App\Services;

use App\DTO\OemInfoDTO;
use App\Exceptions\InvalidOemCodeException;
use App\Exceptions\OemNotFoundException;
use App\Models\Oems;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class OemService
{
    public function getProductInfoFromOems(string $code): OemInfoDTO
    {
        if (empty($code)) {
            throw new InvalidOemCodeException;
        }

        $detailFromOems = Oems::ofCode($code)->first();
        if (! $detailFromOems) {
            throw new OemNotFoundException($code);
        }

        return $this->getInfoAboutDetailFromOems($detailFromOems, $code);
    }

    public function getInfoAboutDetailFromOems(array|Oems $detail, string $searchQuery): OemInfoDTO
    {
        $isStartWithOem = str_starts_with($detail['dt_oem'], $searchQuery);

        return new OemInfoDTO(
            code: ($isStartWithOem) ? $detail['dt_oem'] : $detail['dt_invoice'],
            firm: ($isStartWithOem) ? $detail['fr_code'] : $detail['dt_parent'],
            type: $detail['dt_typec']
        );
    }

    public function findDetailsByQuery(string $searchQuery): Collection
    {
        return $this->buildDetailsQuery($searchQuery)->get();
    }

    public function buildDetailsQuery(string $searchQuery): Builder
    {
        return Oems::query()
            ->where(function (Builder $query) use ($searchQuery): void {
                $query->where('dt_invoice', 'like', "$searchQuery%")
                    ->orWhere('dt_oem', 'like', "$searchQuery%");
            });
    }
}
