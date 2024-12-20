<?php

namespace App\Services;

use App\Models\Detail;
use App\Models\Price;
use App\Models\User;
use Illuminate\Support\Facades\Log;

final class PriceService{
    public function getPrice(int $detailCode, int $userId): int | string {
        $userFormula = User::where('id', '=' ,$userId)->value('formula');
        $price = $this->getPriceByType(strtolower(substr($userFormula, 0, 1)), $detailCode);
        Log::info($price);
        return  $this->getSpecialPrice($price, substr($userFormula, 1,1), substr($userFormula, 2, strpos($userFormula,"%")));
    }

    public function getPriceByType(string $priceType, int $detailCode){
        return ($priceType === 'o') ? Price::where('code', '=', $detailCode)->value('opt') : Price::where('code', '=', $detailCode)->value('zakup');
    }

    public function getSpecialPrice(int | string $price, string $sign, string $percent) {
        if ($sign === ''){
            return $price;
        }
        return  ($sign === '+') ? $price + $price * $percent/100 : $price - $price * $percent/100;
    }
}

