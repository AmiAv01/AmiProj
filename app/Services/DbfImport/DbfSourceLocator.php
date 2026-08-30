<?php

namespace App\Services\DbfImport;

use RuntimeException;
use ZipArchive;

final class DbfSourceLocator
{
    /** @return array{path: string, temporary: bool} */
    public function locate(string $filename, string $sourcePath, ?string $archivePath): array
    {
        if (is_dir($sourcePath)) {
            foreach (scandir($sourcePath) ?: [] as $candidate) {
                if (strcasecmp($candidate, $filename) === 0) {
                    return ['path' => rtrim($sourcePath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$candidate, 'temporary' => false];
                }
            }
        }

        if (is_file($sourcePath) && strcasecmp(basename($sourcePath), $filename) === 0) {
            return ['path' => $sourcePath, 'temporary' => false];
        }

        $archives = [];
        if ($archivePath !== null && $archivePath !== '') {
            if (is_file($archivePath)) {
                $archives[] = $archivePath;
            } elseif (is_dir($archivePath)) {
                $archives = $this->zipFiles($archivePath);
            }
        }
        if (is_dir($sourcePath)) {
            $archives = [...$archives, ...$this->zipFiles($sourcePath)];
        }

        foreach (array_unique($archives) as $archive) {
            $extracted = $this->extract($archive, $filename);
            if ($extracted !== null) {
                return ['path' => $extracted, 'temporary' => true];
            }
        }

        throw new RuntimeException("DBF file {$filename} was not found in {$sourcePath} or its ZIP archives.");
    }

    /** @return list<string> */
    private function zipFiles(string $directory): array
    {
        $lowercase = glob(rtrim($directory, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'*.zip') ?: [];
        $uppercase = glob(rtrim($directory, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'*.ZIP') ?: [];

        return array_values(array_unique([...$lowercase, ...$uppercase]));
    }

    private function extract(string $archivePath, string $filename): ?string
    {
        $archive = new ZipArchive;
        if ($archive->open($archivePath) !== true) {
            throw new RuntimeException("Unable to open DBF archive {$archivePath}.");
        }

        try {
            for ($index = 0; $index < $archive->numFiles; $index++) {
                $entry = $archive->getNameIndex($index);
                if ($entry === false || strcasecmp(basename($entry), $filename) !== 0) {
                    continue;
                }

                $stream = $archive->getStream($entry);
                $temporaryPath = tempnam(sys_get_temp_dir(), 'ami_dbf_');
                if ($stream === false || $temporaryPath === false) {
                    throw new RuntimeException("Unable to extract {$filename} from the archive.");
                }

                $target = fopen($temporaryPath, 'wb');
                if ($target === false) {
                    fclose($stream);
                    throw new RuntimeException("Unable to create a temporary file for {$filename}.");
                }

                stream_copy_to_stream($stream, $target);
                fclose($stream);
                fclose($target);

                return $temporaryPath;
            }
        } finally {
            $archive->close();
        }

        return null;
    }
}
