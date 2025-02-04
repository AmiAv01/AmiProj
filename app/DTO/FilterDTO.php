<?php

namespace App\DTO;

class FilterDTO
{
    public function __construct(public readonly array | string | null $filter)
    {
    }
}
