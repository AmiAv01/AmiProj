<?php

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;

test('registration screen can be rendered', function (): void {
    $this->withoutExceptionHandling();
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register without Auth', function (): void {
    $response = $this->postJson('/api/v1/auth/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phoneNumber' => '1234567890',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    $response->assertCreated()->assertJsonPath('data.email', 'test@example.com');
    $this->assertDatabaseHas('user', [
        'email' => 'test@example.com',
        'notification_email' => 'test@example.com',
    ]);
    $this->assertGuest();
});

test('notification failure does not fail an otherwise successful registration', function (): void {
    Event::listen(Registered::class, function (): void {
        throw new RuntimeException('Notification transport is unavailable.');
    });

    $response = $this->postJson('/api/v1/auth/register', [
        'name' => 'Test User',
        'email' => 'notification-failure@example.com',
        'phoneNumber' => '1234567890',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertCreated()->assertJsonPath('data.email', 'notification-failure@example.com');
    $this->assertDatabaseHas('user', ['email' => 'notification-failure@example.com']);
});
