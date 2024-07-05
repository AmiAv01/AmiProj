<?php

namespace App\Repositories\Interfaces;

interface DetailRepositoryInterface
{
    public function all();

    public function findById(int $id);

    public function findByCategory(array $categories, array $brands);

    public function findBySearching(string $searching, array $brands);

    public function findSameDetails(string $code);
}
