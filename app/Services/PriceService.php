<?php

namespace App\Services;

use App\Exceptions\InvalidPriceTypeException;
use App\Exceptions\PriceNotFoundException;
use App\Enums\PriceType;
use App\Models\Price;
use Money\Money;

final class PriceService
{
    public function __construct(protected UserService $userService, protected CurrencyService $currencyService) {}

    public function getPrice(int $detailCode, int $userId): int | string | Money
    {
        $userFormula = $this->userService->getUserFormula($userId);
        $price = $this->getPriceByType(strtolower(substr($userFormula, 0, 1)), $detailCode);
        if ($price === '') {
            throw new PriceNotFoundException($detailCode);
        }
        $sign = substr($userFormula, 1, 1);
        return  $this->getSpecialPrice($this->parsePrice($price), $sign, $this->getPercent($userFormula));
    }

    private function parsePrice(string $price): string
    {
        return str_replace(',', '.', $price);
    }

    public function getPercent(string $formula): string | int
    {
        return substr($formula, 2, strpos($formula, "%") - 2) ?: 0;
    }

    public function getPriceByType(string $priceTypeString, int $detailCode): string
    {
        $priceType = PriceType::tryFrom($priceTypeString);

        if (!$priceType) {
            throw new InvalidPriceTypeException($priceTypeString);
        }

        return Price::where('code', '=', $detailCode)->value($priceType->getColumn());
    }

    private function getSpecialPrice(string $price, string $sign, string $percent): string
    {
        $currency = $this->currencyService->getCurrency();
        if ($sign === '') {
            return bcmul($price, $this->parsePrice($currency));
        }
        $priceBeforePercent = bcmul($price, $currency);
        $computedPercent =  ($sign === '+') ? bcadd(1, bcdiv($percent, 100, 2), 2) : bcsub(1, bcdiv($percent, 100, 2), 2);
        $endPrice = bcmul($priceBeforePercent, $computedPercent, 2);
        return  $endPrice;
    }
}
