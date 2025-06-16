<?php

namespace App\Services\Product;

use App\Models\Detail;
use App\Models\Oems;

final class AnalogService
{
    public function getAnalogs(string $id): array
    {
        $detailsFromOems = Oems::ofCode($id)->get()->toArray();
        $ids = array_reduce($detailsFromOems, function ($carry, $detail) use ($id) {
            if ($detail['dt_oem'] === $id) {
                $carry[] = $detail['dt_invoice'];
            } elseif ($detail['dt_invoice'] === $id) {
                $carry[] = $detail['dt_oem'];
            }
            return $carry;
        }, []);
        return Detail::whereIn('dt_invoice', $ids)->orWhereIn('dt_oem', $ids)->orWhereIn('dt_cargo', $ids)
            ->join('stk', 'stk.code', '=', 'detail.dt_code')->get()->toArray();
    }

    public function getCargoFromAnalogs(array $details): array
    {
        $codeIds = array_unique(array_merge(
            array_column($details, 'dt_cargo'),
            array_column($details, 'dt_oem'),
            array_column($details, 'dt_invoice')
        ));

        return Oems::whereIn('dt_invoice', $codeIds)
            ->orWhereIn('dt_oem', $codeIds)->where('fr_code', '=', 'CARGO')
            ->where('dt_parent', '=', 'CARGO')->get()->pluck('dt_invoice')->unique()->values()->toArray();
    }
}
