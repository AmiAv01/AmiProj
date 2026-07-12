<?php

namespace App\Services;

use App\Exceptions\InvalidPriceTypeException;
use App\Exceptions\PriceNotFoundException;
use App\Models\Price;
use Illuminate\Support\Facades\Log;

final class PriceService
{
    public function __construct(protected UserService $userService, protected CurrencyService $currencyService) {}

    public function getPrice(int $detailCode, int $userId): string
    {
        $userFormula = $this->userService->getUserFormula($userId);
        $price = $this->getPriceByType(strtolower(substr($userFormula, 0, 1)), $detailCode);
        if ($price === '') {
            throw new PriceNotFoundException($detailCode);
        }
        $sign = substr($userFormula, 1, 1);

        return $this->getSpecialPrice($this->parsePrice($price), $sign, $this->getPercent($userFormula));
    }

    private function parsePrice(string $price): string
    {
        return str_replace(',', '.', $price);
    }

    public function getPercent(string $formula): string
    {
        return substr($formula, 2, strpos($formula, '%') - 2) ?: '0';
    }

    public function getPriceByType(string $priceType, int $detailCode): string
    {
        if (! in_array($priceType, ['o', 'z'], true)) {
            throw new InvalidPriceTypeException($priceType);
        }

        return ($priceType === 'o') ? Price::where('code', '=', $detailCode)->value('opt') : Price::where('code', '=', $detailCode)->value('zakup');
    }

    private function getSpecialPrice(string $price, string $sign, string $percent): string
    {
        $currency = $this->currencyService->getCurrency();
        if ($sign === '') {
            return bcmul($price, $this->parsePrice((string) $currency));
        }
        $priceBeforePercent = bcmul($price, $this->parsePrice((string) $currency));
        Log::info($priceBeforePercent);
        $computedPercent = ($sign === '+') ? bcadd('1', bcdiv((string) $percent, '100', 2), 2) : bcsub('1', bcdiv((string) $percent, '100', 2), 2);
        $endPrice = bcmul($priceBeforePercent, $computedPercent, 2);

        return $endPrice;
    }
}
