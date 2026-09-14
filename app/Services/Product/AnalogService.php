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
            ->first(['dt_id', 'dt_invoice', 'dt_oem', 'dt_cargo', 'dt_typec']);

        $detailType = $this->getDetailType($id, $currentDetail);
        $codes = $this->getRelatedCodes($id, $currentDetail, $detailType);

        $analogsQuery = Detail::query()
            ->where(function (Builder $query) use ($codes): void {
                $query->whereIn('detail.dt_invoice', $codes)
                    ->orWhereIn('detail.dt_oem', $codes)
                    ->orWhereIn('detail.dt_cargo', $codes);
            });

        if ($detailType !== null) {
            $analogsQuery->where('detail.dt_typec', $detailType);
        }

        if ($currentDetail !== null) {
            $analogsQuery->where('detail.dt_id', '<>', $currentDetail->dt_id);
        }

        $analogs = $analogsQuery
            ->join('stk', 'stk.code', '=', 'detail.dt_code')
            ->get()
            ->toArray();

        return $this->sortAnalogs($analogs);
    }

    private function getDetailType(string $id, ?Detail $currentDetail): ?string
    {
        $detailType = $currentDetail === null ? '' : trim((string) $currentDetail->dt_typec);

        if ($detailType === '') {
            $detailType = trim((string) Oems::ofCode($id)->value('dt_typec'));
        }

        return $detailType === '' ? null : $detailType;
    }

    private function getRelatedCodes(string $id, ?Detail $currentDetail, ?string $detailType): array
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
            $code = $this->normalizeCode($code);
            if ($code !== null) {
                $knownCodes[$code] = true;
            }
        }

        $seedCodes = array_keys($knownCodes);
        $relations = Oems::query()
            ->when($detailType !== null, fn (Builder $query) => $query->where('dt_typec', $detailType))
            ->where(function (Builder $query) use ($seedCodes): void {
                $query->whereIn('dt_invoice', $seedCodes)
                    ->orWhereIn('dt_oem', $seedCodes);
            })
            ->get(['dt_invoice', 'dt_oem']);

        foreach ($relations as $relation) {
            foreach ([$relation->dt_invoice, $relation->dt_oem] as $code) {
                $code = $this->normalizeCode($code);
                if ($code !== null) {
                    $knownCodes[$code] = true;
                }
            }
        }

        return array_keys($knownCodes);
    }

    private function normalizeCode(mixed $value): ?string
    {
        $code = trim((string) $value);

        if ($code === '' || preg_match('/^-+$/', $code) === 1) {
            return null;
        }

        return $code;
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
