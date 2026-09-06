<?php

use App\Enums\CurrencyCode;
use App\Services\CurrencyService;

it('creates every configured currency during migration', function (): void {
    expect(app(CurrencyService::class)->getCurrency(CurrencyCode::EUR))->toBe('1')
        ->and(app(CurrencyService::class)->getCurrency(CurrencyCode::USD))->toBe('1.08')
        ->and(app(CurrencyService::class)->getCurrency(CurrencyCode::RUB))->toBe('105.5');
});
