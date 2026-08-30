<?php

return [
    'source_path' => env('DBF_SOURCE_PATH', storage_path('app/dbf')),
    'archive_path' => env('DBF_ARCHIVE_PATH'),
    'encryption_key' => env('DBF_ENCRYPTION_KEY'),
    'batch_size' => max(1, (int) env('DBF_BATCH_SIZE', 1000)),
    'process_memory_limit' => env('DBF_PROCESS_MEMORY_LIMIT', '256M'),
    'cluster_scheduler' => (bool) env('DBF_CLUSTER_SCHEDULER', false),
];
