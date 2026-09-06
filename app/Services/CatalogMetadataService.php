<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\CatalogMetadataDto;

class CatalogMetadataService
{
    public function getMetadata(string $type, ?string $category = null): CatalogMetadataDto
    {
        $configKey = $category ? "parts.filters.{$type}.{$category}" : "parts.filters.{$type}";
        $filters = config($configKey, []);

        $titleKey = $category ? "title.{$type}_{$category}" : "title.{$type}";
        $title = __($titleKey);

        return new CatalogMetadataDto($filters, $title);
    }
}
