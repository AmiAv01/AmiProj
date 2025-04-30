<?php

namespace App\Services\Interface;

interface ProductViewServiceInterface
{
    public function getViewDataForProduct(string $id): array;
    public function resolveViewName(): string;
}
