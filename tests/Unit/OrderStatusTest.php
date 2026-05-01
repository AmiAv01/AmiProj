<?php

use App\Enums\OrderStatus;

covers(OrderStatus::class);

test('order status enum exposes all allowed values', function (): void {
    expect(OrderStatus::values())->toBe([
        'Новый',
        'Принят',
        'Выполнен',
    ]);
});
