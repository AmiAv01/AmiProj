<?php

test('', function (): void {
    $this->withoutExceptionHandling();
    $response = $this->get('/info');

    $response->assertStatus(200);
});
