<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\CatalogMetadataDto;

class CatalogMetadataService
{
    public function getMetadata(string $type, ?string $category = null): CatalogMetadataDto
    {
        $configKey = $category ? "parts.filters.{$category}.$type" : "parts.filters.{$type}";
        $filters = config($configKey, []);

        $titleKey = $category ? "title.{$category}_{$type}" : "title.{$type}";
        $title = __($titleKey);

        return new CatalogMetadataDto($filters, $title);
    }
}
