<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('internal command creates an approved and verified user', function (): void {
    $this->artisan('user:create', [
        '--name' => 'Internal User',
        '--email' => 'INTERNAL@example.com',
        '--phone' => '+375291234567',
    ])
        ->expectsQuestion('Password', 'secure-password')
        ->expectsQuestion('Repeat password', 'secure-password')
        ->expectsOutputToContain('Created user #')
        ->assertSuccessful();

    $user = User::where('email', 'internal@example.com')->firstOrFail();

    expect($user->name)->toBe('Internal User')
        ->and($user->approved)->toBeTrue()
        ->and($user->isAdmin)->toBeFalse()
        ->and($user->email_verified_at)->not->toBeNull()
        ->and(Hash::check('secure-password', $user->password))->toBeTrue();
});

test('internal command can create an unapproved administrator', function (): void {
    $this->artisan('user:create', [
        '--name' => 'Internal Admin',
        '--email' => 'admin@example.com',
        '--phone' => '+375291234567',
        '--admin' => true,
        '--unapproved' => true,
    ])
        ->expectsQuestion('Password', 'secure-password')
        ->expectsQuestion('Repeat password', 'secure-password')
        ->expectsOutputToContain('Created administrator #')
        ->expectsOutputToContain('The account is not approved')
        ->assertSuccessful();

    $user = User::where('email', 'admin@example.com')->firstOrFail();

    expect($user->approved)->toBeFalse()
        ->and($user->isAdmin)->toBeTrue();
});

test('internal command rejects an existing email', function (): void {
    User::factory()->create(['email' => 'existing@example.com']);

    $this->artisan('user:create', [
        '--name' => 'Duplicate User',
        '--email' => 'existing@example.com',
        '--phone' => '+375291234567',
    ])
        ->expectsQuestion('Password', 'secure-password')
        ->expectsQuestion('Repeat password', 'secure-password')
        ->assertExitCode(2);

    expect(User::where('email', 'existing@example.com')->count())->toBe(1);
});
