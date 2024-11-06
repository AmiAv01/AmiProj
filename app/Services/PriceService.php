<?php

namespace App\Services;

use App\Models\Detail;
use App\Models\Oems;
use Illuminate\Support\Facades\Log;

final class PriceService{
    public function getPrice(){

    }

    public function isUserSpecialPrice(int $userPrice):bool
    {
        return ($userPrice) ? true : false;
    }

    public function isSpecialPrice():bool
    {

    }
}

