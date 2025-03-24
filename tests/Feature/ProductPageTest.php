<?php


use App\Models\User;

test('return successful response when getting product from auth user', function () {
    $user = User::factory()->create([
        'approved' => true
    ]);
    $response = $this->actingAs($user)->get("/catalog/product/CS1277");
    $response->assertStatus(200);
});

