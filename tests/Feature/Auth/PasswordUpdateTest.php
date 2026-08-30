<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('password can be updated', function (): void {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->putJson('/api/v1/profile/password', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response->assertOk();

    $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
});

test('correct password must be provided to update password', function (): void {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->putJson('/api/v1/profile/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response->assertUnprocessable()->assertJsonValidationErrors('current_password');
});
