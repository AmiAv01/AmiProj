<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\Price;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Log;
use Money\Money;

final class PriceService{
    public function getPrice(int $detailCode, int $userId): int | string | Money {
        $userFormula = User::where('id', '=' ,$userId)->value('formula');
        $price = $this->getPriceByType(strtolower(substr($userFormula, 0, 1)), $detailCode);
        Log::info($price);
        return  $this->getSpecialPrice($price, substr($userFormula, 1,1), substr($userFormula, 2, strpos($userFormula,"%")));
    }

    public function parsePrice(string $price){
        return str_replace(',','.',$price);
    }

    public function getPriceByType(string $priceType, int $detailCode): string{
        return ($priceType === 'o') ? Price::where('code', '=', $detailCode)->value('opt') : Price::where('code', '=', $detailCode)->value('zakup');
    }

    public function getSpecialPrice(string $price, string $sign, string $percent): string
    {
        if ($sign === ''){
            return $price;
        }
        $currency = Crypt::decrypt(Currency::where('code', '=', 'EUR')->value('value')->get());
        $computedPercent = bcmul($price, $percent / 100);
        return  ($sign === '+') ? bcmul(bcadd($price, $computedPercent), $currency) : bcmul(bcsub($price, $computedPercent), $currency);
    }
}

