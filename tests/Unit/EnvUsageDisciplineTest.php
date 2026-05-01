<?php

test('env helper is not used in app directory', function (): void {
    $directory = new RecursiveDirectoryIterator(__DIR__.'/../../app');
    $iterator = new RecursiveIteratorIterator($directory);

    foreach ($iterator as $fileInfo) {
        if (! $fileInfo->isFile() || $fileInfo->getExtension() !== 'php') {
            continue;
        }

        $content = file_get_contents($fileInfo->getPathname());
        expect($content)->not->toContain('env(');
    }
})->coversNothing();
