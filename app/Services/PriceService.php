<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\Price;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Log;
use Money\Money;

final class PriceService{
    public function __construct(protected UserService $userService)
    {}

    public function getPrice(int $detailCode, int $userId): int | string | Money {
        $userFormula = $this->userService->getUserFormula($userId);
        $price = $this->getPriceByType(strtolower(substr($userFormula, 0, 1)), $detailCode);
        $sign = substr($userFormula, 1,1);
        return  $this->getSpecialPrice($this->parsePrice($price), $sign, $this->getPercent($userFormula));
    }

    private function parsePrice(string $price):string{
        return str_replace(',','.',$price);
    }

    public function getPercent(string $formula): string | int
    {
        return substr($formula, 2, strpos($formula,"%") - 2) ?: 0;
    }

    public function getPriceByType(string $priceType, int $detailCode): string{
        return ($priceType === 'o') ? Price::where('code', '=', $detailCode)->value('opt') : Price::where('code', '=', $detailCode)->value('zakup');
    }

    private function getSpecialPrice(string $price, string $sign, string $percent): string
    {
        $currency = Currency::where('code', '=', 'EUR')->value('value');
        if ($sign === ''){
            return bcmul($price, $this->parsePrice($currency));
        }
        $priceBeforePercent = bcmul($price, $currency);
        $computedPercent =  ($sign === '+') ? bcadd(1, bcdiv($percent, 100, 2), 2) : bcsub(1, bcdiv($percent, 100, 2), 2);
        $endPrice = bcmul($priceBeforePercent, $computedPercent, 2);
        return  $endPrice;
    }
}

