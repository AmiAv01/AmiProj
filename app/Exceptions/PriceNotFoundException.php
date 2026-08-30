<?php

namespace App\Exceptions;

class PriceNotFoundException extends NotFoundException
{
    public function __construct(int $detailCode)
    {
        parent::__construct("Price not found for detail code: {$detailCode}");
    }
}
