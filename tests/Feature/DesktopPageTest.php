<?php

test('', function () {
    $this->withoutExceptionHandling();
    $response = $this->get('/desktop');
    $response->assertStatus(200);
});
