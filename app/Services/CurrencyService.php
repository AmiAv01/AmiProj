<?php

namespace App\Services;

use App\DTO\CurrencyDTO;
use App\Exceptions\CurrencyNotFoundException;
use App\Models\Currency;
use Illuminate\Support\Facades\Crypt;

final class CurrencyService
{
    public function getCurrency(): string{
        $currency = Currency::where('code', '=', 'EUR')->value('value');
        if (!$currency) {
            throw new CurrencyNotFoundException('EUR');
        }
        return Crypt::decrypt($currency);
    }

    public function update(CurrencyDTO $dto):bool
    {
        $currency = Currency::where('code', '=', 'EUR')->first();
        if (!$currency) {
            throw new CurrencyNotFoundException('EUR');
        }
        return Currency::where('code', '=', 'EUR')->first()->update(['value' => Crypt::encrypt($dto->currency)]);
    }
}
