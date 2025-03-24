<?php


test('test generators page', function () {
    $response = $this->get('/catalog/generators');
    $response->assertStatus(200);
});

test('test starters page', function () {
    $response = $this->get('/catalog/starters');
    $response->assertStatus(200);
});

test('test bearings page', function () {
    $response = $this->get('/catalog/bearings');
    $response->assertStatus(200);
});

test('test starter parts page', function () {
    $response = $this->get('/catalog/starter_parts/fork');
    $response->assertStatus(200);
});

test('test generator parts page', function () {
    $response = $this->get('/catalog/generator_parts/sleeve');
    $response->assertStatus(200);
});

test('test other details page', function () {
    $response = $this->get('/catalog/other');
    $response->assertStatus(200);
});





