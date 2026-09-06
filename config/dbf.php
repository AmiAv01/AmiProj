<?php

$legacyConfigPath = base_path('DbfParsers/config.php');
$legacyConfig = is_file($legacyConfigPath) ? require $legacyConfigPath : [];
$encryptionKey = env('DBF_ENCRYPTION_KEY');

return [
    'source_path' => env('DBF_SOURCE_PATH', storage_path('app/dbf')),
    'archive_path' => env('DBF_ARCHIVE_PATH'),
    'encryption_key' => is_string($encryptionKey) && $encryptionKey !== ''
        ? $encryptionKey
        : ($legacyConfig['encryption_key'] ?? null),
    'batch_size' => max(1, (int) env('DBF_BATCH_SIZE', 1000)),
    'process_memory_limit' => env('DBF_PROCESS_MEMORY_LIMIT', '256M'),
    'cluster_scheduler' => (bool) env('DBF_CLUSTER_SCHEDULER', false),
];
