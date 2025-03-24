<?php


use App\Models\Detail;
use App\Models\User;

test('return successful response when getting product from auth user', function () {
    $this->withoutExceptionHandling();
    $user = User::factory()->create([
        'approved' => true
    ]);
    Detail::factory()->create(['dt_invoice' => 'CS1277']);
    $response = $this->actingAs($user)->get("/catalog/product/CS1277");
    $response->assertStatus(200);
});

