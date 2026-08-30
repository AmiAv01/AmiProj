<?php

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
    $this->assertGuest();
});
