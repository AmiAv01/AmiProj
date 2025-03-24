<?php

it('returns a successful response', function (): void {
    $this->withoutExceptionHandling();
    $response = $this->get('/');

    $response->assertStatus(200);
});
