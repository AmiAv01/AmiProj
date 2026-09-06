<?php

namespace App\DTO;

readonly class CatalogMetadataDto
{
    /** @param list<string> $filters */
    public function __construct(
        public array $filters,
        public string $title,
    ) {}
}
