<?php

test('news page displays', function (): void {
    $this->withoutExceptionHandling();
    $response = $this->get('/news');

    $response->assertStatus(200);
});
