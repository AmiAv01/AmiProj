<?php

namespace App\Console\Commands;

use App\Services\DbfImport\DbfBrandImporter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Throwable;

final class SyncDbfBrandsCommand extends Command
{
    protected $signature = 'dbf:sync-brands
        {--source= : Override DBF_SOURCE_PATH}
        {--archive= : Override DBF_ARCHIVE_PATH}';

    protected $description = 'Replace the brand list with unique FIRMS values from ASS.DBF';

    public function handle(DbfBrandImporter $importer): int
    {
        $lock = Cache::lock('dbf-import:sync', 3600);
        if (! $lock->get()) {
            $this->error('Another DBF import is already running.');

            return self::FAILURE;
        }

        try {
            $count = $importer->sync(
                $this->option('source') ?: null,
                $this->option('archive') ?: null,
            );
            $this->info("Brands updated from ASS.DBF: {$count} unique values.");

            return self::SUCCESS;
        } catch (Throwable $exception) {
            report($exception);
            $this->error($exception->getMessage());

            return self::FAILURE;
        } finally {
            $lock->release();
        }
    }
}
