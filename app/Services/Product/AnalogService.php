<?php

namespace App\Services\Product;

use App\Models\Detail;
use App\Models\Oems;
use Illuminate\Database\Eloquent\Builder;

final class AnalogService
{
    public function getAnalogs(string $id): array
    {
        $currentDetail = Detail::query()
            ->where('dt_invoice', $id)
            ->first(['dt_id', 'dt_invoice', 'dt_oem', 'dt_cargo']);

        $codes = $this->getRelatedCodes($id, $currentDetail);

        $analogsQuery = Detail::query()
            ->where(function (Builder $query) use ($codes): void {
                $query->whereIn('detail.dt_invoice', $codes)
                    ->orWhereIn('detail.dt_oem', $codes)
                    ->orWhereIn('detail.dt_cargo', $codes);
            });

        if ($currentDetail !== null) {
            $analogsQuery->where('detail.dt_id', '<>', $currentDetail->dt_id);
        }

        $analogs = $analogsQuery
            ->join('stk', 'stk.code', '=', 'detail.dt_code')
            ->get()
            ->toArray();

        return $this->sortAnalogs($analogs);
    }

    private function getRelatedCodes(string $id, ?Detail $currentDetail): array
    {
        $seedCodes = [$id];

        if ($currentDetail !== null) {
            $seedCodes = array_merge($seedCodes, [
                $currentDetail->dt_invoice,
                $currentDetail->dt_oem,
                $currentDetail->dt_cargo,
            ]);
        }

        $knownCodes = [];
        foreach ($seedCodes as $code) {
            if (trim((string) $code) !== '') {
                $knownCodes[(string) $code] = true;
            }
        }

        $frontier = array_keys($knownCodes);

        while ($frontier !== []) {
            $relations = Oems::query()
                ->where(function (Builder $query) use ($frontier): void {
                    $query->whereIn('dt_invoice', $frontier)
                        ->orWhereIn('dt_oem', $frontier);
                })
                ->get(['dt_invoice', 'dt_oem']);

            $nextFrontier = [];
            foreach ($relations as $relation) {
                foreach ([$relation->dt_invoice, $relation->dt_oem] as $code) {
                    $code = (string) $code;
                    if ($code === '' || isset($knownCodes[$code])) {
                        continue;
                    }

                    $knownCodes[$code] = true;
                    $nextFrontier[] = $code;
                }
            }

            $frontier = $nextFrontier;
        }

        return array_keys($knownCodes);
    }

    private function sortAnalogs(array $analogList): array
    {
        usort($analogList, function ($firstEl, $secondEl) {
            $hasFirstStk = ! empty($firstEl['ostc']);
            $hasSecondStk = ! empty($secondEl['ostc']);

            return $hasSecondStk <=> $hasFirstStk;
        });

        return $analogList;
    }

    public function getCargoFromAnalogs(array $details): array
    {
        $codeIds = array_unique(array_merge(
            array_column($details, 'dt_cargo'),
            array_column($details, 'dt_oem'),
            array_column($details, 'dt_invoice')
        ));

        return Oems::query()
            ->where(function ($query) use ($codeIds): void {
                $query->whereIn('dt_invoice', $codeIds)
                    ->orWhereIn('dt_oem', $codeIds);
            })
            ->where('fr_code', '=', 'CARGO')
            ->where('dt_parent', '=', 'CARGO')
            ->pluck('dt_invoice')
            ->unique()
            ->values()
            ->all();
    }
}
