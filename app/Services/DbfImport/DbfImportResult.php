<?php

namespace App\Services\DbfImport;

final readonly class DbfImportResult
{
    public function __construct(
        public string $filename,
        public string $status,
        public int $recordsRead = 0,
        public int $recordsWritten = 0,
    ) {}
}
