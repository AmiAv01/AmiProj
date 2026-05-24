<?php

namespace App\Services\Product;

use App\Models\AltCz;
use App\Models\Detail;
use App\Models\RozCz;

class CompatiblePartsService
{
    public function getCompatibleParts(string $detailType, string $detailInvoice): array
    {
        return match ($detailType) {
            'ГЕНЕРАТОР' => $this->getGeneratorCompatibleParts($detailInvoice),
            'СТАРТЕР' => $this->getStarterCompatibleParts($detailInvoice),
            default => [],
        };
    }

    private function getGeneratorCompatibleParts(string $detailInvoice): array
    {
        $ids = AltCz::where('hcparts', $detailInvoice)->orWhere('tmp', $detailInvoice)->get()
            ->reject(fn ($item) => str_starts_with($item->dt_code, '-') || empty($item->dt_code))
            ->pluck('dt_code')->unique()->values()->toArray();

        return $this->getDetailsByCodes($ids);
    }

    private function getStarterCompatibleParts(string $detailInvoice): array
    {
        $ids = RozCz::where('hcparts', $detailInvoice)->orWhere('tmp', $detailInvoice)->get()
            ->reject(fn ($item) => str_starts_with($item->dt_code, '-') || empty($item->dt_code))
            ->pluck('dt_code')->unique()->values()->toArray();

        return $this->getDetailsByCodes($ids);
    }

    private function getDetailsByCodes(array $codes): array
    {
        $details = Detail::whereIn('detail.dt_cargo', $codes)
            ->orWhereIn('detail.dt_invoice', $codes)
            ->orWhereIn('detail.dt_oem', $codes)
            ->select([
                'detail.dt_id',
                'detail.dt_invoice',
                'detail.dt_typec',
                'detail.dt_cargo',
                'detail.fr_code',
                'detail.dt_code',
                'detail.dt_foto',
                'stk.ostc as stock_quantity',
            ])
            ->leftJoin('stk', 'stk.code', '=', 'detail.dt_code')
            ->get()
            ->toArray();

        usort($details, function ($a, $b) {
            $qtyA = trim($a['stock_quantity'] ?? '');
            $qtyB = trim($b['stock_quantity'] ?? '');

            $hasStockA = ($qtyA !== '' && $qtyA !== '0' && mb_strtolower($qtyA) !== 'нет' && mb_strtolower($qtyA) !== 'нет в наличии');
            $hasStockB = ($qtyB !== '' && $qtyB !== '0' && mb_strtolower($qtyB) !== 'нет' && mb_strtolower($qtyB) !== 'нет в наличии');

            if ($hasStockA === $hasStockB) {
                return 0;
            }

            return $hasStockA ? -1 : 1;
        });

        return $details;
    }
}
