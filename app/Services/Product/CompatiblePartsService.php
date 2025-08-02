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
            ->reject(fn($item) => str_starts_with($item->dt_code, '-') || empty($item->dt_code))
            ->pluck('dt_code')->unique()->values()->toArray();

        return $this->getDetailsByCodes($ids);
    }

    private function getStarterCompatibleParts(string $detailInvoice): array
    {
        $ids = RozCz::where('hcparts', $detailInvoice)->orWhere('tmp', $detailInvoice)->get()
            ->reject(fn($item) => str_starts_with($item->dt_code, '-') || empty($item->dt_code))
            ->pluck('dt_code')->unique()->values()->toArray();
        return $this->getDetailsByCodes($ids);
    }

    private function getDetailsByCodes(array $codes): array
    {
        return Detail::whereIn('dt_cargo', $codes)
            ->orWhereIn('dt_invoice', $codes)
            ->orWhereIn('dt_oem', $codes)
            ->select(['dt_invoice', 'dt_typec',  'dt_cargo', 'fr_code'])->get()->toArray();
    }
}
