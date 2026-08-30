<?php

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
