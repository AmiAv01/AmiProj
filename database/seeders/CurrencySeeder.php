<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rates = config('currency.rates', []);

        foreach ($rates as $code => $value) {
            Currency::updateOrCreate(
                ['code' => $code], ['value' => Crypt::encrypt($value)],
            );
        }
    }
}
