<?php


test('', function () {
    $this->withoutExceptionHandling();
    $response = $this->get('/info');

    $response->assertStatus(200);
});
