<?php

test('news page displays', function () {
    $this->withoutExceptionHandling();
    $response = $this->get('/news');

    $response->assertStatus(200);
});
