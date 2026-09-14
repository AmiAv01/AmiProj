<?php

namespace App\Services\DbfImport;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class DbfImportReportService
{
    /** @return array{runs: LengthAwarePaginator, selectedRun: ?object, issues: ?LengthAwarePaginator, summary: array<string, int>} */
    public function report(?int $runId = null): array
    {
        $runs = DB::table('dbf_import_runs')
            ->orderByDesc('started_at')
            ->orderByDesc('id')
            ->paginate(15, ['*'], 'runs_page')
            ->withPath('/admin/resource/imports')
            ->withQueryString();

        $selectedRunQuery = DB::table('dbf_import_runs')
            ->whereRaw('UPPER(filename) = ?', ['ASS.DBF'])
            ->where('status', 'completed');
        $selectedRun = $runId === null
            ? $selectedRunQuery->latest('id')->first()
            : $selectedRunQuery->where('id', $runId)->first();

        if ($selectedRun === null) {
            return ['runs' => $runs, 'selectedRun' => null, 'issues' => null, 'summary' => $this->emptySummary()];
        }

        $summaryRow = DB::table('dbf_import_issues')
            ->where('run_id', $selectedRun->id)
            ->selectRaw('COUNT(*) as positions')
            ->selectRaw('COALESCE(SUM(missing_internal_code), 0) as internal_code')
            ->selectRaw('COALESCE(SUM(missing_invoice), 0) as invoice')
            ->selectRaw('COALESCE(SUM(missing_cargo), 0) as cargo')
            ->selectRaw('COALESCE(SUM(missing_oem), 0) as oem')
            ->selectRaw('COALESCE(SUM(missing_photo), 0) as photo')
            ->first();

        $issues = DB::table('dbf_import_issues')
            ->where('run_id', $selectedRun->id)
            ->orderBy('detail_id')
            ->paginate(50, ['*'], 'issues_page')
            ->withPath('/admin/resource/imports')
            ->withQueryString()
            ->through(function (object $issue): object {
                $issue->missing_fields = array_values(array_filter([
                    $issue->missing_internal_code ? 'Внутренний код' : null,
                    $issue->missing_invoice ? 'Артикул (Invoice)' : null,
                    $issue->missing_cargo ? 'Код CARGO' : null,
                    $issue->missing_oem ? 'Код OEM' : null,
                    $issue->missing_photo ? 'Фото' : null,
                ]));

                return $issue;
            });

        return [
            'runs' => $runs,
            'selectedRun' => $selectedRun,
            'issues' => $issues,
            'summary' => [
                'positions' => (int) ($summaryRow->positions ?? 0),
                'internal_code' => (int) ($summaryRow->internal_code ?? 0),
                'invoice' => (int) ($summaryRow->invoice ?? 0),
                'cargo' => (int) ($summaryRow->cargo ?? 0),
                'oem' => (int) ($summaryRow->oem ?? 0),
                'photo' => (int) ($summaryRow->photo ?? 0),
            ],
        ];
    }

    /** @return array<string, int> */
    private function emptySummary(): array
    {
        return ['positions' => 0, 'internal_code' => 0, 'invoice' => 0, 'cargo' => 0, 'oem' => 0, 'photo' => 0];
    }
}
