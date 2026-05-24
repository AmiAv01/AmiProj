<?php

namespace App\Exceptions;

use Exception;

class PriceNotFoundException extends Exception
{
    public function __construct(int $detailCode)
    {
        parent::__construct("Price not found for detail code: {$detailCode}");
    }
}
