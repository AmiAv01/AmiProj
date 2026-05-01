<?php

namespace App\Services;

use App\DTO\CurrencyDTO;
use App\Enums\CurrencyCode;
use App\Exceptions\CurrencyNotFoundException;
use App\Models\Currency;
use Illuminate\Support\Facades\Crypt;

final class CurrencyService
{
    public function getCurrency(CurrencyCode $code = CurrencyCode::EUR): string
    {
        $currency = Currency::where('code', '=', $code->value)->value('value');

        if (! $currency) {
            throw new CurrencyNotFoundException(__('currency_not_found', ['code' => $code->value]));
        }

        return Crypt::decrypt($currency);
    }

    public function update(CurrencyDTO $dto, CurrencyCode $code = CurrencyCode::EUR): bool
    {
        $currency = Currency::where('code', '=', $code->value)->first();

        if (! $currency) {
            throw new CurrencyNotFoundException(__('Currency :code not found', ['code' => $code->value]));
        }

        return $currency->update(['value' => Crypt::encrypt($dto->currency)]);
    }
}
