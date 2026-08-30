<?php

use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;

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

test('login attempts are rate limited by email and ip', function (): void {
    $user = User::factory()->create(['approved' => true]);
    $key = mb_strtolower($user->email).'|127.0.0.1';
    RateLimiter::clear($key);

    foreach (range(1, 5) as $_attempt) {
        $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertUnprocessable();
    }

    expect(RateLimiter::tooManyAttempts($key, 5))->toBeTrue();

    $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertUnprocessable()->assertJsonValidationErrors('email');

    $this->assertGuest();
    RateLimiter::clear($key);
});

test('authenticated user responses expose only the public contract', function (): void {
    $user = User::factory()->create(['approved' => true]);

    $this->actingAs($user)
        ->getJson('/api/v1/auth/user')
        ->assertOk()
        ->assertJsonStructure(['data' => ['id', 'name', 'email', 'email_verified_at', 'approved', 'isAdmin']])
        ->assertJsonMissingPath('data.formula')
        ->assertJsonMissingPath('data.phone_number')
        ->assertJsonMissingPath('data.created_at');
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
