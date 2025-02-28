<?php

test('news page displays', function () {
    $response = $this->get('/news');

    $response->assertStatus(200);
});
