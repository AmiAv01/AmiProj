<?php


test('return successful response when getting product', function () {
    $response = $this->get('/catalog/product/');

    $response->assertStatus(200);
});

test('return successful response when getting product from starter parts', function () {
    $response = $this->get('/catalog/starter_parts/product/{id}');

    $response->assertStatus(200);
});


test('return successful response when getting product from starter parts', function () {
    $response = $this->get('/catalog/starter_parts/product/{id}');

    $response->assertStatus(200);
});
