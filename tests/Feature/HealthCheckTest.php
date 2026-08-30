<?php

it('reports liveness without checking dependencies', function (): void {
    $this->getJson('/api/health/live')
        ->assertOk()
        ->assertJsonPath('status', 'ok');
});

it('reports readiness when required services are available', function (): void {
    $this->getJson('/api/health/ready')
        ->assertOk()
        ->assertJson([
            'status' => 'ready',
            'checks' => [
                'database' => true,
                'cache' => true,
                'queue' => true,
            ],
        ]);
});
