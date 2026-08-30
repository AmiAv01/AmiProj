<?php

namespace App\Console\Commands;

use App\Services\DbfImport\DbfImporter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Process\Process;
use Throwable;

class SyncDbfCommand extends Command
{
    protected $signature = 'dbf:sync
        {--file=* : Import only these DBF filenames}
        {--source= : Override DBF_SOURCE_PATH}
        {--archive= : Override DBF_ARCHIVE_PATH}
        {--force : Import even when the file checksum has not changed}';

    protected $description = 'Synchronize legacy DBF files into application tables';

    public function handle(DbfImporter $importer): int
    {
        $memoryLimit = (string) config('dbf.process_memory_limit', '256M');
        if (! preg_match('/^\d+[KMG]$/i', $memoryLimit)) {
            $memoryLimit = '256M';
        }
        ini_set('memory_limit', $memoryLimit);

        $requestedFiles = array_values(array_filter($this->option('file'), is_string(...)));
        if (count($requestedFiles) !== 1) {
            return $this->runIsolated($requestedFiles !== [] ? $requestedFiles : DbfImporter::FILES, $memoryLimit);
        }

        $lock = Cache::lock('dbf-import:sync', 3600);
        if (! $lock->get()) {
            $this->warn('Another DBF import is already running.');

            return self::SUCCESS;
        }

        $failed = false;
        $files = $requestedFiles;

        try {
            foreach ($files as $file) {
                try {
                    $result = $importer->sync(
                        [(string) $file],
                        (bool) $this->option('force'),
                        $this->option('source') ?: null,
                        $this->option('archive') ?: null,
                    )[0];

                    if ($result->status === 'skipped') {
                        $this->line("{$result->filename}: unchanged, skipped");
                    } else {
                        $this->info("{$result->filename}: {$result->recordsRead} DBF records read, {$result->recordsWritten} SQL rows synchronized");
                    }
                } catch (Throwable $exception) {
                    $failed = true;
                    report($exception);
                    $this->error("{$file}: {$exception->getMessage()}");
                }
            }
        } finally {
            $lock->release();
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }

    /** @param list<string> $files */
    private function runIsolated(array $files, string $memoryLimit): int
    {
        $failed = false;

        foreach ($files as $file) {
            $arguments = [PHP_BINARY, '-d', "memory_limit={$memoryLimit}", base_path('artisan'), 'dbf:sync', "--file={$file}"];
            foreach (['source', 'archive'] as $option) {
                if (is_string($this->option($option)) && $this->option($option) !== '') {
                    $arguments[] = "--{$option}={$this->option($option)}";
                }
            }
            if ($this->option('force')) {
                $arguments[] = '--force';
            }

            $process = new Process($arguments, base_path(), null, null, null);
            $exitCode = $process->run(fn (string $type, string $output) => $this->output->write($output));
            $failed = $failed || $exitCode !== self::SUCCESS;
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
