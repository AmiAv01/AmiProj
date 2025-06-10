<?php

namespace App\Services\Product;

use App\Exceptions\DefaultImageNotFoundException;
use App\Exceptions\ImageStorageException;
use App\Exceptions\InvalidImagePathException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductImageService
{
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
        foreach (explode(',', $imagePaths) as $imagePath) {
            $normalizedPath = $this->normalizeImagePath($imagePath);
            try {
                if (Storage::disk('images')->exists($normalizedPath . '.jpg')) {
                    Log::info("Found image: {$normalizedPath}.jpg");
                    return $normalizedPath;
                }
            } catch (\Exception $e) {
                throw new ImageStorageException("Failed to access image storage: " . $e->getMessage());
            }
        }
        return null;
    }

    private function normalizeImagePath(string $path): string
    {
        if (empty(trim($path))) {
            throw new InvalidImagePathException($path);
        }
        $path = strtolower($path);
        return stristr($path, ',', true) ?: $path;
    }

    private function getDefaultImageUrl(): string
    {
        $defaultImage = '/no-photo--lg.png';

        if (!file_exists(public_path($defaultImage))) {
            throw new DefaultImageNotFoundException();
        }

        return url($defaultImage);
    }

    private function buildImageUrl(string $imagePath): string
    {
        if (empty($imagePath)) {
            throw new InvalidImagePathException($imagePath);
        }
        return url('storage/images/' . $imagePath . '.jpg');
    }
}
