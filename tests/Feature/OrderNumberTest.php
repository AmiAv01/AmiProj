<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;

it('assigns a short unique public number to every order', function (): void {
    $user = User::factory()->create();

    $first = Order::create([
        'total_price' => '10.00',
        'status' => OrderStatus::NEW->value,
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);
    $second = Order::create([
        'total_price' => '20.00',
        'status' => OrderStatus::NEW->value,
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);

    expect($first->order_number)
        ->toMatch('/^ORD-[23456789ABCDEFGHJKLMNPQRSTUVWXYZ]{10}$/')
        ->not->toBe($second->order_number);
});

it('uses the public number to open an order without weakening ownership checks', function (): void {
    $owner = User::factory()->create(['approved' => true]);
    $otherUser = User::factory()->create(['approved' => true]);
    $order = Order::create([
        'total_price' => '10.00',
        'status' => OrderStatus::NEW->value,
        'created_by' => $owner->id,
        'updated_by' => $owner->id,
    ]);

    $this->actingAs($owner)
        ->getJson("/api/v1/orders/{$order->order_number}")
        ->assertOk()
        ->assertJsonPath('data.order.order_number', $order->order_number);

    $this->actingAs($otherUser)
        ->getJson("/api/v1/orders/{$order->order_number}")
        ->assertNotFound();
});
