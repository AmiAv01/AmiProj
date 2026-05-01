<?php

test('', function (): void {
    $this->withoutExceptionHandling();
    $response = $this->get('/desktop');
    $response->assertStatus(200);
});
