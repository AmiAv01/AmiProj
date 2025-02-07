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
        $userFormula = Crypt::decrypt(User::where('id', '=' ,$userId)->value('formula'));
        $price = $this->getPriceByType(strtolower(substr($userFormula, 0, 1)), $detailCode);
        Log::info($price);
        return  $this->getSpecialPrice($price, substr($userFormula, 1,1), $this->getPercent($userFormula));
    }

    private function parsePrice(string $price):string{
        return str_replace(',','.',$price);
    }

    public function getPercent(string $formula): string | int
    {
        return substr($formula, 2, strpos($formula,"%")) ?: 0;
    }

    public function getPriceByType(string $priceType, int $detailCode): string{
        return ($priceType === 'o') ? Price::where('code', '=', $detailCode)->value('opt') : Price::where('code', '=', $detailCode)->value('zakup');
    }

    private function getSpecialPrice(string $price, string $sign, string $percent): string
    {
        $currency = Currency::where('code', '=', 'EUR')->value('value');
        if ($sign === ''){
            return bcmul($this->parsePrice($price), $this->parsePrice($currency));
        }
        $computedPercent = bcmul($price, $percent / 100);
        Log::info($computedPercent);
        Log::info($currency);
        return  ($sign === '+') ? bcmul(bcadd($price, $computedPercent), $currency) : bcmul(bcsub($price, $computedPercent), $currency);
    }
}

