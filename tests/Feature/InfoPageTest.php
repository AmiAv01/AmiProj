<?php


test('', function () {
    $response = $this->get('/info');

    $response->assertStatus(200);
});
