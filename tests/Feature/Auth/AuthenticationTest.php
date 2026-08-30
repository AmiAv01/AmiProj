<?php

use App\Models\User;

test('login screen can be rendered', function (): void {
    $this->withoutExceptionHandling();
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function (): void {
    $user = User::factory()->create([
        'approved' => true,
    ]);
    $response = $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertOk()->assertJsonPath('data.id', $user->id);
});

test('users can`t authenticate using the login screen when approved field is false', function (): void {
    $user = User::factory()->create([
        'approved' => false,
    ]);
    $response = $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertGuest();
    $response->assertUnprocessable();
});

test('users can not authenticate with invalid password', function (): void {
    $user = User::factory()->create();

    $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/v1/auth/logout');

    $this->assertGuest();
    $response->assertOk();
});
