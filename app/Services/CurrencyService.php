<?php

namespace App\Services;

use App\DTO\CurrencyDTO;
use App\Models\Currency;

final class CurrencyService
{
    public function update(CurrencyDTO $dto):bool
    {
        return Currency::where('code', '=', 'EUR')->first()->update(['value' => $dto->currency]);
    }
}
