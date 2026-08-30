<?php

namespace App\Services\DbfImport;

use DateTimeInterface;
use Illuminate\Support\Facades\DB;
use org\majkel\dbase\Table;
use RuntimeException;
use Throwable;

final class DbfImporter
{
    private ?LegacyCrypt $crypt = null;

    /** @var list<string> */
    public const FILES = ['FIRMS.DBF', 'ASS.DBF', 'OEMS.DBF', 'ALT_CZ.DBF', 'ROZ_CZ.DBF', 'DATA.DBF', 'stk.dbf'];

    /** @var array<string, string> */
    private const PART_TYPES = [
        'AWIR' => 'Якорь', 'AWIR_OM_OR' => 'Якорь', 'AWIR_CG' => 'Якорь',
        'AUZW' => 'Статор', 'AUZW_OM_OR' => 'Статор', 'AUZW_CG' => 'Статор',
        'AREG' => 'Регулятор напряжения', 'AREG_HU_TR' => 'Регулятор напряжения', 'AREG_CG' => 'Регулятор напряжения',
        'APRO' => 'Выпрямитель', 'APRO_HU_TR' => 'Выпрямитель', 'APRO_CG' => 'Выпрямитель',
        'ALOZP' => 'Подшипник', 'ALOZP_CG' => 'Подшипник', 'ALOZT' => 'Подшипник', 'ALOZT_CG' => 'Подшипник',
        'ADEKP' => 'Крышка передняя', 'ADEKP_CG' => 'Крышка передняя', 'ADEKT' => 'Крышка задняя', 'ADEKT_CG' => 'Крышка задняя',
        'APOK' => 'Крышка пластиковая', 'APOK_CG' => 'Крышка пластиковая', 'ASZC' => 'Щетки', 'ASZC_EU' => 'Щетки',
        'ATRZ' => 'Щеткодержатель', 'ATRZ_HU' => 'Щеткодержатель', 'ATRZ_CG' => 'Щеткодержатель', 'AKOL' => 'Шкив', 'AKOL_CG' => 'Шкив',
        'RAUT' => 'Соленоид', 'RAUT_CG' => 'Соленоид', 'RAUT1' => 'Соленоид', 'RAUT1_CG' => 'Соленоид',
        'RBEN' => 'Привод', 'RBEN_GH' => 'Привод', 'RBEN_CG' => 'Привод', 'RWIR' => 'Якорь', 'RWIR_OM_OR' => 'Якорь', 'RWIR_CG' => 'Якорь',
        'RUZW' => 'Статор', 'RUZW_OM_OR' => 'Статор', 'RUZW_CG' => 'Статор', 'RTRZ' => 'Щеткодержатель', 'RTRZ_IK' => 'Щеткодержатель', 'RTRZ_CG' => 'Щеткодержатель',
        'RSZC' => 'Щетки', 'RSZC_EU' => 'Щетки', 'RSZC_CG' => 'Щетки', 'RGLO' => 'Крышка передняя', 'RGLO_CG' => 'Крышка передняя',
        'RDEK' => 'Крышка задняя', 'RDEK_CG' => 'Крышка задняя', 'RTULP' => 'Втулка', 'RTULP_CG' => 'Втулка', 'RTULT' => 'Втулка', 'RTULT_CG' => 'Втулка',
        'RTUL_ZES' => 'Втулка', 'RTUL_CG' => 'Втулка', 'RWID' => 'Вилка', 'RWID_CG' => 'Вилка', 'RPOD' => 'Крышка', 'RPOD_CG' => 'Крышка',
        'RPRZ' => 'Планетарная передача', 'RPRZ_GH' => 'Планетарная передача', 'RPRZ_CG' => 'Планетарная передача',
        'RBIE' => 'Шестерня', 'RBIE_CG' => 'Шестерня', 'RLOZ' => 'Подшипник', 'RLOZ_CG' => 'Подшипник',
    ];

    public function __construct(private readonly DbfSourceLocator $locator) {}

    /** @param list<string>|null $filenames
     * @return list<DbfImportResult>
     */
    public function sync(?array $filenames = null, bool $force = false, ?string $sourcePath = null, ?string $archivePath = null): array
    {
        $sourcePath ??= (string) config('dbf.source_path');
        $archivePath ??= config('dbf.archive_path');
        $results = [];

        foreach ($filenames ?? self::FILES as $filename) {
            $results[] = $this->import($this->canonicalFilename($filename), $force, $sourcePath, $archivePath);
        }

        return $results;
    }

    private function import(string $filename, bool $force, string $sourcePath, ?string $archivePath): DbfImportResult
    {
        $source = $this->locator->locate($filename, $sourcePath, $archivePath);
        $path = $source['path'];
        $startedAt = now();
        $runId = null;

        try {
            $sha256 = hash_file('sha256', $path);
            if ($sha256 === false) {
                throw new RuntimeException("Unable to calculate a checksum for {$filename}.");
            }

            $runId = DB::table('dbf_import_runs')->insertGetId([
                'filename' => $filename,
                'sha256' => $sha256,
                'status' => 'running',
                'started_at' => $startedAt,
                'created_at' => $startedAt,
                'updated_at' => $startedAt,
            ]);

            $previousHash = DB::table('dbf_import_files')->where('filename', $filename)->value('sha256');
            if (! $force && hash_equals((string) $previousHash, $sha256)) {
                $this->finishRun($runId, 'skipped', 0, 0);

                return new DbfImportResult($filename, 'skipped');
            }

            [$recordsRead, $recordsWritten] = $this->writeFile($filename, $path);
            $modifiedAt = filemtime($path);
            $finishedAt = now();

            DB::table('dbf_import_files')->upsert([[
                'filename' => $filename,
                'sha256' => $sha256,
                'size' => filesize($path) ?: 0,
                'source_modified_at' => $modifiedAt === false ? null : date('Y-m-d H:i:s', $modifiedAt),
                'imported_at' => $finishedAt,
                'created_at' => $finishedAt,
                'updated_at' => $finishedAt,
            ]], ['filename'], ['sha256', 'size', 'source_modified_at', 'imported_at', 'updated_at']);

            $this->finishRun($runId, 'completed', $recordsRead, $recordsWritten);

            return new DbfImportResult($filename, 'completed', $recordsRead, $recordsWritten);
        } catch (Throwable $exception) {
            if ($runId !== null) {
                DB::table('dbf_import_runs')->where('id', $runId)->update([
                    'status' => 'failed',
                    'error' => mb_substr($exception->getMessage(), 0, 65000),
                    'finished_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            throw $exception;
        } finally {
            if ($source['temporary']) {
                @unlink($path);
            }
        }
    }

    /** @return array{int, int} */
    private function writeFile(string $filename, string $path): array
    {
        $table = Table::fromFile($path);
        $fields = $table->getFieldsNames();
        $batchSize = (int) config('dbf.batch_size', 1000);
        $timestamp = now()->toDateTimeString();
        $batches = [];
        $recordsRead = 0;
        $recordsWritten = 0;

        foreach ($table as $record) {
            if ($record->isDeleted()) {
                continue;
            }

            $recordsRead++;
            foreach ($this->mapRecord($filename, $fields, $record, $timestamp) as $mapped) {
                $batches[$mapped['table']][] = $mapped['row'];
                if (count($batches[$mapped['table']]) >= $batchSize) {
                    $recordsWritten += $this->flush($mapped['table'], $batches[$mapped['table']]);
                    $batches[$mapped['table']] = [];
                }
            }
        }

        foreach ($batches as $tableName => $rows) {
            $recordsWritten += $this->flush($tableName, $rows);
        }

        return [$recordsRead, $recordsWritten];
    }

    /** @return list<array{table: string, row: array<string, mixed>}> */
    private function mapRecord(string $filename, array $fields, mixed $record, string $timestamp): array
    {
        return match (strtoupper($filename)) {
            'FIRMS.DBF' => [[
                'table' => 'firm',
                'row' => ['fr_code' => $this->integer($record, 'CODE'), 'fr_name' => $this->text($record, 'TYPE', true), 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ]],
            'ASS.DBF' => $this->detailRows($record, $timestamp),
            'OEMS.DBF' => [$this->oemRow($record, $timestamp)],
            'ALT_CZ.DBF' => $this->compatibleRows('alt_cz', $fields, $record, false, $timestamp),
            'ROZ_CZ.DBF' => $this->compatibleRows('roz_cz', $fields, $record, true, $timestamp),
            'DATA.DBF' => [$this->priceRow($record, $timestamp)],
            'STK.DBF' => [[
                'table' => 'stk',
                'row' => ['code' => $this->integer($record, 'CODE'), 'ostc' => $this->text($record, 'OSTC'), 'ost' => $this->text($record, 'OST'), 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ]],
            default => throw new RuntimeException("No importer is registered for {$filename}."),
        };
    }

    private function detailRows(mixed $record, string $timestamp): array
    {
        $code = $this->integer($record, 'CODE');
        $type = $this->text($record, 'TYPE', true);
        $acode = $this->integer($record, 'ACODE');

        return [
            ['table' => 'detail', 'row' => [
                'dbf_source_key' => $this->sourceKey([$code]),
                'dt_code' => $code, 'dt_extcode' => $this->integer($record, 'EXTCODE'), 'dt_extname' => $this->integer($record, 'EXTNAME'),
                'dt_type' => $type, 'dt_comment' => $this->text($record, 'COMMENT', true), 'dt_foto' => $this->text($record, 'FOTO'),
                'dt_invoice' => $this->text($record, 'INVOICE'), 'dt_netto' => $this->integer($record, 'NETTO'), 'dt_oem' => $this->text($record, 'OEM'),
                'dt_baza' => $this->integer($record, 'BAZA'), 'dt_cena' => $this->integer($record, 'CENA'), 'dt_prod' => $this->integer($record, 'PROD'),
                'dt_typec' => $this->text($record, 'TYPEC', true), 'dt_bp' => $this->integer($record, 'BP'), 'dt_cargo' => $this->text($record, 'CARGO'),
                'dt_e' => $this->integer($record, 'E'), 'dt_hs' => $this->integer($record, 'HS'), 'dt_datep' => $this->date($record, 'DATEP', '1970-01-01'),
                'dt_name' => $this->text($record, 'NAME', true) ?: $type, 'fr_code' => $this->text($record, 'FIRMS'),
                'dt_tp_ptype' => $this->integer($record, 'PTYPE'), 'lt_dt_acode' => $acode, 'created_at' => $timestamp, 'updated_at' => $timestamp,
            ]],
            ['table' => 'layout_for_details', 'row' => ['lt_dt_acode' => $acode, 'created_at' => $timestamp, 'updated_at' => $timestamp]],
        ];
    }

    private function oemRow(mixed $record, string $timestamp): array
    {
        $invoice = $this->text($record, 'INVOICE', true);
        $oem = $this->text($record, 'OEM', true);
        $brand = $this->text($record, 'BRAND', true);

        return ['table' => 'oems', 'row' => [
            'dbf_source_key' => $this->sourceKey([$invoice, $oem]), 'dt_invoice' => $invoice,
            'dt_parent' => $this->text($record, 'PARENT', true), 'dt_oem' => $oem, 'fr_code' => $brand,
            'dt_typec' => $this->text($record, 'TYPE_RUS', true), 'created_at' => $timestamp, 'updated_at' => $timestamp,
        ]];
    }

    private function priceRow(mixed $record, string $timestamp): array
    {
        $this->crypt ??= new LegacyCrypt((string) config('dbf.encryption_key'));

        return ['table' => 'price', 'row' => [
            'code' => $this->integer($record, 'CODE'), 'zakup' => $this->crypt->decrypt($this->raw($record, 'XF')),
            'opt' => $this->crypt->decrypt($this->raw($record, 'XFO')), 'prod' => $this->crypt->decrypt($this->raw($record, 'XFR')),
            'created_at' => $timestamp, 'updated_at' => $timestamp,
        ]];
    }

    private function compatibleRows(string $table, array $fields, mixed $record, bool $keepEmpty, string $timestamp): array
    {
        $rows = [];
        $metadataColumns = ['ID', 'DATEP', 'TMP', 'HCPARTS', 'BRAND'];
        foreach (array_values(array_diff($fields, $metadataColumns)) as $column) {
            foreach (array_filter(array_map('trim', explode(',', $this->text($record, $column)))) as $detailCode) {
                [$detailBrand, $code] = str_contains($detailCode, '#') ? explode('#', $detailCode, 2) : ['CARGO', $detailCode];
                $identity = [$this->text($record, 'TMP'), $this->text($record, 'HCPARTS'), $this->text($record, 'BRAND'), self::PART_TYPES[$column] ?? 'Прочее', $detailBrand, $code];
                $rows[] = ['table' => $table, 'row' => $this->compatibleRow($record, $identity, $detailBrand, $code, $detailCode, $timestamp)];
            }
        }

        if ($keepEmpty && $rows === []) {
            $identity = [$this->text($record, 'TMP'), $this->text($record, 'HCPARTS'), $this->text($record, 'BRAND'), '', '', ''];
            $rows[] = ['table' => $table, 'row' => $this->compatibleRow($record, $identity, '', '', '', $timestamp)];
        }

        return $rows;
    }

    private function compatibleRow(mixed $record, array $identity, string $detailBrand, string $code, string $originalCode, string $timestamp): array
    {
        return [
            'dbf_source_key' => $this->sourceKey($identity), 'datep' => $this->date($record, 'DATEP', '1970-01-01 00:00:00', true),
            'tmp' => $identity[0], 'hcparts' => $identity[1], 'brand' => $identity[2], 'typec' => $identity[3],
            'dt_brand' => $detailBrand, 'dt_code' => $code, 'img' => $originalCode === '' ? '' : (str_contains($originalCode, '#') ? $originalCode : "CARGO#{$originalCode}"),
            'created_at' => $timestamp, 'updated_at' => $timestamp,
        ];
    }

    private function flush(string $table, array $rows): int
    {
        if ($rows === []) {
            return 0;
        }

        $uniqueBy = match ($table) {
            'firm' => ['fr_code'], 'stk', 'price' => ['code'], 'layout_for_details' => ['lt_dt_acode'],
            default => ['dbf_source_key'],
        };
        $update = array_values(array_diff(array_keys($rows[0]), [...$uniqueBy, 'created_at']));
        DB::table($table)->upsert($rows, $uniqueBy, $update);

        return count($rows);
    }

    private function canonicalFilename(string $filename): string
    {
        foreach (self::FILES as $known) {
            if (strcasecmp($known, basename($filename)) === 0) {
                return $known;
            }
        }

        throw new RuntimeException("Unsupported DBF file: {$filename}.");
    }

    private function finishRun(int $runId, string $status, int $read, int $written): void
    {
        DB::table('dbf_import_runs')->where('id', $runId)->update([
            'status' => $status, 'records_read' => $read, 'records_written' => $written,
            'finished_at' => now(), 'updated_at' => now(),
        ]);
    }

    private function sourceKey(array $parts): string
    {
        return hash('sha256', implode("\x1F", array_map(static fn (mixed $part): string => (string) $part, $parts)));
    }

    private function raw(mixed $record, string $field): string
    {
        return isset($record[$field]) ? (string) $record[$field] : '';
    }

    private function text(mixed $record, string $field, bool $convert = false): string
    {
        $value = trim($this->raw($record, $field));
        if (! $convert || $value === '') {
            return $value;
        }

        $converted = iconv('CP866', 'UTF-8//IGNORE', $value);

        return $converted === false ? $value : trim($converted);
    }

    private function integer(mixed $record, string $field): int
    {
        return (int) $this->raw($record, $field);
    }

    private function date(mixed $record, string $field, string $fallback, bool $withTime = false): string
    {
        $value = $record[$field] ?? null;
        if ($value instanceof DateTimeInterface) {
            return $value->format($withTime ? 'Y-m-d H:i:s' : 'Y-m-d');
        }

        $timestamp = is_string($value) && $value !== '' ? strtotime($value) : false;

        return $timestamp === false ? $fallback : date($withTime ? 'Y-m-d H:i:s' : 'Y-m-d', $timestamp);
    }
}
