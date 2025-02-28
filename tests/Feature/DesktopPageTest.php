<?php

test('', function () {
    $response = $this->get('/desktop');
    $response->assertStatus(200);
});
