<?php

use App\Providers\RouteServiceProvider;

test('registration screen can be rendered', function (): void {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register without Auth', function (): void {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phoneNumber' => '1234567890',
        'password' => 'password',
        'password_confirmation' => 'password'
    ]);
    $response->assertRedirect(RouteServiceProvider::HOME);
});
