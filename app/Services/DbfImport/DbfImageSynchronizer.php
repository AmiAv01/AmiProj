<?php

namespace App\Services\DbfImport;

use FilesystemIterator;
use Illuminate\Support\Facades\Storage;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use SplFileInfo;
use ZipArchive;

final class DbfImageSynchronizer
{
    /** @var list<string> */
    private const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    public function sync(string $sourcePath, ?string $archivePath = null): int
    {
        $paths = array_values(array_unique(array_filter([$sourcePath, $archivePath], static fn (?string $path): bool => $path !== null && $path !== '')));
        $archives = [];
        $written = 0;

        foreach ($paths as $path) {
            if (is_dir($path)) {
                [$directoryWritten, $directoryArchives] = $this->syncDirectory($path);
                $written += $directoryWritten;
                $archives = [...$archives, ...$directoryArchives];
            } elseif (is_file($path) && strcasecmp(pathinfo($path, PATHINFO_EXTENSION), 'zip') === 0) {
                $archives[] = $path;
            }
        }

        foreach (array_unique($archives) as $archive) {
            $written += $this->syncArchive($archive);
        }

        return $written;
    }

    /** @return array{int, list<string>} */
    private function syncDirectory(string $directory): array
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
        );
        $archives = [];
        $written = 0;

        /** @var SplFileInfo $file */
        foreach ($iterator as $file) {
            if (! $file->isFile()) {
                continue;
            }

            $extension = strtolower($file->getExtension());
            if ($extension === 'zip') {
                $archives[] = $file->getPathname();
            } elseif (in_array($extension, self::IMAGE_EXTENSIONS, true)) {
                $written += $this->copyFile($file->getPathname(), $file->getSize(), $file->getMTime());
            }
        }

        return [$written, $archives];
    }

    private function syncArchive(string $archivePath): int
    {
        $archive = new ZipArchive;
        if ($archive->open($archivePath) !== true) {
            throw new RuntimeException("Unable to open image archive {$archivePath}.");
        }

        $written = 0;
        try {
            for ($index = 0; $index < $archive->numFiles; $index++) {
                $stat = $archive->statIndex($index);
                if ($stat === false || str_ends_with($stat['name'], '/')) {
                    continue;
                }

                $extension = strtolower(pathinfo($stat['name'], PATHINFO_EXTENSION));
                if (! in_array($extension, self::IMAGE_EXTENSIONS, true)) {
                    continue;
                }

                $destination = $this->destinationName($stat['name']);
                $modifiedAt = $stat['mtime'];
                if (! $this->needsCopy($destination, (int) $stat['size'], $modifiedAt)) {
                    continue;
                }

                $stream = $archive->getStream($stat['name']);
                if ($stream === false) {
                    throw new RuntimeException("Unable to read image {$stat['name']} from {$archivePath}.");
                }

                try {
                    $this->writeStream($destination, $stream, $modifiedAt);
                    $written++;
                } finally {
                    fclose($stream);
                }
            }
        } finally {
            $archive->close();
        }

        return $written;
    }

    private function copyFile(string $source, int $size, int $modifiedAt): int
    {
        $destination = $this->destinationName($source);
        if (! $this->needsCopy($destination, $size, $modifiedAt)) {
            return 0;
        }

        $stream = fopen($source, 'rb');
        if ($stream === false) {
            throw new RuntimeException("Unable to read product image {$source}.");
        }

        try {
            $this->writeStream($destination, $stream, $modifiedAt);
        } finally {
            fclose($stream);
        }

        return 1;
    }

    /** @param resource $stream */
    private function writeStream(string $destination, mixed $stream, int $modifiedAt): void
    {
        $disk = Storage::disk('images');
        if (! $disk->put($destination, $stream)) {
            throw new RuntimeException("Unable to store product image {$destination}.");
        }

        if ($modifiedAt > 0) {
            @touch($disk->path($destination), $modifiedAt);
        }
    }

    private function needsCopy(string $destination, int $sourceSize, int $sourceModifiedAt): bool
    {
        $disk = Storage::disk('images');
        if (! $disk->exists($destination)) {
            return true;
        }

        if ($disk->size($destination) !== $sourceSize) {
            return true;
        }

        return $sourceModifiedAt > 0 && $disk->lastModified($destination) < $sourceModifiedAt;
    }

    private function destinationName(string $source): string
    {
        return strtolower(basename(str_replace('\\', '/', $source)));
    }
}
