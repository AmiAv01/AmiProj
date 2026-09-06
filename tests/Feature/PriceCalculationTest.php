<?php

use App\Models\User;
use App\Services\PriceService;
use Illuminate\Support\Facades\DB;

it('keeps decimal precision when calculating a price without a percentage', function (): void {
    $user = User::factory()->create();
    DB::table('price')->insert([
        'code' => 4344,
        'zakup' => '0.366',
        'opt' => '0.512',
        'prod' => '1.024',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    expect(app(PriceService::class)->getPrice(4344, $user->id))->toBe('0.51');
});
