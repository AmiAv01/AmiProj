<?php

namespace App\Services\Product;

use App\Exceptions\DefaultImageNotFoundException;
use App\Exceptions\ImageStorageException;
use App\Exceptions\InvalidImagePathException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductImageService
{
    const string DEFAULT_IMAGE_URL = '/no-photo--lg.png';

    /** @var list<string> */
    private const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    /**
     * @throws InvalidImagePathException
     * @throws ImageStorageException
     * @throws DefaultImageNotFoundException
     */
    public function getImageUrl(?string $imageName = null): string
    {
        if (empty($imageName)) {
            return $this->getDefaultImageUrl();
        }
        $foundImage = $this->findExistingImage($imageName);

        return $foundImage ? $this->buildImageUrl($foundImage) : $this->getDefaultImageUrl();
    }

    /**
     * @throws InvalidImagePathException
     * @throws ImageStorageException
     */
    private function findExistingImage(string $imagePaths): ?string
    {
        foreach (array_filter(array_map('trim', explode(',', $imagePaths))) as $imagePath) {
            try {
                foreach ($this->candidatePaths($imagePath) as $candidate) {
                    if (Storage::disk('images')->exists($candidate)) {
                        return $candidate;
                    }
                }
            } catch (\Exception $e) {
                Log::error('Failed to access product image storage.', ['exception' => $e]);
                throw new ImageStorageException('Failed to access image storage.', $e);
            }
        }

        return null;
    }

    /** @return list<string> */
    private function candidatePaths(string $path): array
    {
        $filename = basename(str_replace('\\', '/', trim($path)));
        if ($filename === '') {
            throw new InvalidImagePathException($path);
        }

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $stem = in_array($extension, self::IMAGE_EXTENSIONS, true)
            ? pathinfo($filename, PATHINFO_FILENAME)
            : $filename;
        $extensions = in_array($extension, self::IMAGE_EXTENSIONS, true)
            ? [$extension]
            : self::IMAGE_EXTENSIONS;
        $candidates = [];

        foreach (array_unique([$stem, strtolower($stem), strtoupper($stem)]) as $candidateStem) {
            foreach ($extensions as $candidateExtension) {
                $candidates[] = "{$candidateStem}.{$candidateExtension}";
                $candidates[] = "{$candidateStem}.".strtoupper($candidateExtension);
            }
        }

        return array_values(array_unique($candidates));
    }

    private function getDefaultImageUrl(): string
    {
        if (! file_exists(public_path(self::DEFAULT_IMAGE_URL))) {
            throw new DefaultImageNotFoundException;
        }

        return url(self::DEFAULT_IMAGE_URL);
    }

    private function buildImageUrl(string $imagePath): string
    {
        if (empty($imagePath)) {
            throw new InvalidImagePathException($imagePath);
        }

        return url('storage/images/'.$imagePath);
    }
}
