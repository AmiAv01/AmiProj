<?php

namespace App\DTO;

readonly class CatalogMetadataDto
{
    public function __construct(
        public array $filters,
        public string $title,
    ) {}
}
