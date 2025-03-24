<?php

namespace App\Services;

use App\DTO\CurrencyDTO;
use App\Models\Currency;
use Illuminate\Support\Facades\Crypt;

final class CurrencyService
{
    public function getCurrency(): string{
        return Crypt::decrypt(Currency::where('code', '=', 'EUR')->value('value'));
    }

    public function update(CurrencyDTO $dto):bool
    {
        return Currency::where('code', '=', 'EUR')->first()->update(['value' => Crypt::encrypt($dto->currency)]);
    }
}
