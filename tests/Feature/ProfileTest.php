<?php

use App\Models\News;
use App\Models\Order;
use App\Models\User;

test('profile page is displayed', function (): void {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->getJson('/api/v1/profile');

    $response->assertOk();
});

test('profile information can be updated', function (): void {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patchJson('/api/v1/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response->assertOk();

    $user->refresh();

    $this->assertSame('Test User', $user->name);
    $this->assertSame('test@example.com', $user->email);
    $this->assertNull($user->email_verified_at);
});

test('email verification status is unchanged when the email address is unchanged', function (): void {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patchJson('/api/v1/profile', [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response->assertOk();

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function (): void {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->deleteJson('/api/v1/profile', [
            'password' => 'password',
        ]);

    $response->assertNoContent();

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('deleting an account preserves historical orders and news', function (): void {
    $user = User::factory()->create();
    $order = Order::create([
        'total_price' => '25.00',
        'status' => 'Новый',
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);
    $news = News::create([
        'title' => 'Historical post',
        'description' => 'Historical description',
        'date' => now(),
        'author' => $user->id,
    ]);

    $this->actingAs($user)->delete('/api/v1/profile', [
        'password' => 'password',
    ])->assertNoContent();

    $this->assertDatabaseMissing('user', ['id' => $user->id]);
    $this->assertDatabaseHas('order', ['id' => $order->id, 'created_by' => null, 'updated_by' => null]);
    $this->assertDatabaseHas('news', ['id' => $news->id, 'author' => null]);
});

test('correct password must be provided to delete account', function (): void {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->deleteJson('/api/v1/profile', [
            'password' => 'wrong-password',
        ]);

    $response->assertUnprocessable()->assertJsonValidationErrors('password');

    $this->assertNotNull($user->fresh());
});
