<?php

namespace App\Services\DbfImport;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use org\majkel\dbase\Table;
use RuntimeException;

final class DbfBrandImporter
{
    public function __construct(private readonly DbfSourceLocator $locator) {}

    public function sync(?string $sourcePath = null, ?string $archivePath = null): int
    {
        $sourcePath ??= (string) config('dbf.source_path');
        $archivePath ??= config('dbf.archive_path');
        $source = $this->locator->locate('ASS.DBF', $sourcePath, $archivePath);

        try {
            $brands = $this->readUniqueBrands($source['path']);
            if ($brands === []) {
                throw new RuntimeException('ASS.DBF does not contain any non-empty FIRMS values. The existing brands were not changed.');
            }

            $timestamp = now()->toDateTimeString();
            DB::transaction(function () use ($brands, $timestamp): void {
                DB::table('firm')->delete();

                foreach (array_chunk($brands, 500) as $chunk) {
                    DB::table('firm')->insert(array_map(static fn (string $brand): array => [
                        'fr_name' => $brand,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ], $chunk));
                }
            });

            Cache::forget('firms.all');

            return count($brands);
        } finally {
            if ($source['temporary']) {
                @unlink($source['path']);
            }
        }
    }

    /** @return list<string> */
    private function readUniqueBrands(string $path): array
    {
        $table = Table::fromFile($path);
        if (! in_array('FIRMS', array_map('strtoupper', $table->getFieldsNames()), true)) {
            throw new RuntimeException('The FIRMS column was not found in ASS.DBF.');
        }

        $unique = [];
        foreach ($table as $record) {
            if ($record->isDeleted()) {
                continue;
            }

            $brand = $this->brand($record);
            if ($brand === '') {
                continue;
            }

            $unique[mb_strtolower($brand, 'UTF-8')] ??= $brand;
        }

        $brands = array_values($unique);
        usort($brands, strnatcasecmp(...));

        return $brands;
    }

    private function brand(mixed $record): string
    {
        $value = trim(isset($record['FIRMS']) ? (string) $record['FIRMS'] : '');
        if ($value === '' || mb_check_encoding($value, 'UTF-8')) {
            return $value;
        }

        $converted = iconv('CP866', 'UTF-8//IGNORE', $value);

        return $converted === false ? $value : trim($converted);
    }
}
