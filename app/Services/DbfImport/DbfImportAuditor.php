<?php

namespace App\Services\DbfImport;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

final class DbfImportAuditor
{
    public function recordDetailIssues(int $runId, string $importTimestamp): int
    {
        $issuesCount = 0;
        $createdAt = now()->toDateTimeString();

        $this->problemDetails($importTimestamp)->chunkById(500, function ($details) use ($runId, $createdAt, &$issuesCount): void {
            $issues = [];

            foreach ($details as $detail) {
                $missingInternalCode = (int) $detail->dt_code === 0;
                $missingInvoice = $this->isBlank($detail->dt_invoice);
                $missingCargo = $this->isBlank($detail->dt_cargo);
                $missingOem = $this->isBlank($detail->dt_oem);
                $missingPhoto = $this->isBlank($detail->dt_foto);

                if (! $missingInternalCode && ! $missingInvoice && ! $missingCargo && ! $missingOem && ! $missingPhoto) {
                    continue;
                }

                $issues[] = [
                    'run_id' => $runId,
                    'detail_id' => $detail->dt_id,
                    'detail_code' => (int) $detail->dt_code,
                    'invoice' => trim((string) $detail->dt_invoice),
                    'product_name' => trim((string) ($detail->dt_typec ?: $detail->dt_type ?: $detail->dt_name)),
                    'brand' => trim((string) $detail->fr_code),
                    'missing_internal_code' => $missingInternalCode,
                    'missing_invoice' => $missingInvoice,
                    'missing_cargo' => $missingCargo,
                    'missing_oem' => $missingOem,
                    'missing_photo' => $missingPhoto,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ];
            }

            if ($issues !== []) {
                DB::table('dbf_import_issues')->insert($issues);
                $issuesCount += count($issues);
            }
        }, 'dt_id');

        return $issuesCount;
    }

    private function problemDetails(string $importTimestamp): Builder
    {
        return DB::table('detail')
            ->select(['dt_id', 'dt_code', 'dt_invoice', 'dt_cargo', 'dt_oem', 'dt_foto', 'dt_typec', 'dt_type', 'dt_name', 'fr_code'])
            ->where('updated_at', $importTimestamp)
            ->whereNull('deleted_at')
            ->where(function (Builder $query): void {
                $query->where('dt_code', 0)
                    ->orWhereRaw("TRIM(COALESCE(dt_invoice, '')) = ''")
                    ->orWhereRaw("TRIM(COALESCE(dt_cargo, '')) = ''")
                    ->orWhereRaw("TRIM(COALESCE(dt_oem, '')) = ''")
                    ->orWhereRaw("TRIM(COALESCE(dt_foto, '')) = ''");
            })
            ->orderBy('dt_id');
    }

    private function isBlank(mixed $value): bool
    {
        return trim((string) $value) === '';
    }
}
