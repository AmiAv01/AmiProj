<?php

it('serves the Vue SPA for browser routes', function (): void {
    $this->get('/')
        ->assertOk()
        ->assertViewIs('spa')
        ->assertSee('id="app"', false);

    $this->get('/catalog/generators')->assertOk()->assertViewIs('spa');
    $this->get('/admin/resource/login')->assertOk()->assertViewIs('spa');
})->coversNothing();

it('protects authenticated JSON endpoints', function (): void {
    $this->getJson('/api/v1/auth/user')->assertUnauthorized();
    $this->getJson('/api/v1/cart')->assertUnauthorized();
    $this->getJson('/api/v1/admin/dashboard')->assertUnauthorized();
})->coversNothing();

it('returns JSON validation errors from authentication endpoints', function (): void {
    $this->postJson('/api/v1/auth/login', [])->assertUnprocessable()->assertJsonValidationErrors(['email', 'password']);
    $this->postJson('/api/v1/auth/register', [])->assertUnprocessable()->assertJsonValidationErrors(['name', 'email', 'phoneNumber', 'password']);
})->coversNothing();
