<?php

use App\Models\User;

test('confirm password screen can be rendered', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/confirm-password');

    $response->assertStatus(200);
});

test('password can be confirmed', function (): void {
    $this->withoutExceptionHandling();
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/v1/auth/confirm-password', [
        'password' => 'password',
    ]);

    $response->assertOk();
});

test('password is not confirmed with invalid password', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/v1/auth/confirm-password', [
        'password' => 'wrong-password',
    ]);

    $response->assertUnprocessable()->assertJsonValidationErrors('password');
});
