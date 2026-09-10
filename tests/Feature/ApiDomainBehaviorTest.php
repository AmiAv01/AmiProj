<?php

use App\Enums\OrderStatus;
use App\Models\Detail;
use App\Models\News;
use App\Models\Order;
use App\Models\User;
use App\Services\CatalogMetadataService;
use App\Services\Product\AnalogService;
use App\Services\Product\ProductImageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

it('resolves nested catalog filters and translated titles in type-category order', function (): void {
    $metadata = app(CatalogMetadataService::class)->getMetadata('starter_parts', 'fork');

    expect($metadata->filters)->toBe(['ВИЛКА СТАРТЕРА'])
        ->and($metadata->title)->toBe('Вилки стартера');
});

it('rejects unknown and missing nested catalog categories before querying', function (): void {
    $this->getJson('/api/v1/catalog/starter_parts/not-a-category')
        ->assertUnprocessable()
        ->assertJsonValidationErrors('category');

    $this->getJson('/api/v1/catalog/starter_parts')
        ->assertUnprocessable()
        ->assertJsonValidationErrors('category');
});

it('returns domain not-found exceptions with a 404 response', function (): void {
    $user = User::factory()->create(['approved' => true]);

    $this->actingAs($user)
        ->getJson('/api/v1/orders/999999')
        ->assertNotFound();
});

it('does not expose another customer order by direct id', function (): void {
    $owner = User::factory()->create(['approved' => true]);
    $otherUser = User::factory()->create(['approved' => true]);
    $order = Order::create([
        'total_price' => '10.00',
        'status' => OrderStatus::NEW->value,
        'created_by' => $owner->id,
        'updated_by' => $owner->id,
    ]);

    $this->actingAs($otherUser)
        ->getJson("/api/v1/orders/{$order->id}")
        ->assertNotFound();
});

it('returns missing admin users as 404 instead of decrypting an empty formula', function (): void {
    $admin = User::factory()->create(['approved' => true, 'isAdmin' => true]);

    $this->actingAs($admin)
        ->getJson('/api/v1/admin/users/999999')
        ->assertNotFound();
});

it('does not let an administrator delete itself through the administration API', function (): void {
    $admin = User::factory()->create(['isAdmin' => true]);

    $this->actingAs($admin)
        ->deleteJson("/api/v1/admin/users/{$admin->id}")
        ->assertUnprocessable()
        ->assertJsonValidationErrors('user');

    $this->assertNotNull($admin->fresh());
});

it('returns the newly created news item on the first admin page', function (): void {
    $admin = User::factory()->create(['approved' => true, 'isAdmin' => true]);

    foreach (range(1, 12) as $index) {
        News::create([
            'title' => "Existing news {$index}",
            'description' => "Existing description {$index}",
            'date' => now()->subDay(),
            'author' => $admin->id,
        ]);
    }

    $this->actingAs($admin)
        ->postJson('/api/v1/admin/news', [
            'title' => 'Newly created news',
            'description' => 'Newly created description',
        ])
        ->assertOk()
        ->assertJsonPath('data.items.data.0.title', 'Newly created news')
        ->assertJsonCount(12, 'data.items.data');
});

it('records the admin who changes an order to every supported status', function (): void {
    $customer = User::factory()->create(['approved' => true]);
    $admin = User::factory()->create(['approved' => true, 'isAdmin' => true]);
    $order = Order::create([
        'total_price' => '10.00',
        'status' => OrderStatus::NEW->value,
        'created_by' => $customer->id,
        'updated_by' => $customer->id,
    ]);

    $this->actingAs($admin)
        ->putJson("/api/v1/admin/orders/{$order->id}", ['status' => OrderStatus::DONE->value])
        ->assertOk()
        ->assertJsonPath('data.status', OrderStatus::DONE->value)
        ->assertJsonPath('data.updated_by', $admin->id);

    $this->assertDatabaseHas('order', [
        'id' => $order->id,
        'status' => OrderStatus::DONE->value,
        'updated_by' => $admin->id,
    ]);
});

it('rejects abusive cart quantities before executing cart logic', function (): void {
    $user = User::factory()->create(['approved' => true]);

    $this->actingAs($user)
        ->putJson('/api/v1/cart/1', ['quantity' => 1000])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('quantity');
});

it('rejects cart quantities below one instead of deleting the item', function (): void {
    $user = User::factory()->create(['approved' => true]);

    $this->actingAs($user)
        ->putJson('/api/v1/cart/1', ['quantity' => 0])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('quantity');
});

it('applies cargo ownership filters to both sides of the analog lookup', function (): void {
    $now = now();
    DB::table('oems')->insert([
        [
            'dt_invoice' => 'MATCH',
            'dt_parent' => 'OTHER',
            'dt_oem' => 'WRONG-OEM',
            'fr_code' => 'OTHER',
            'dt_typec' => 'TYPE',
            'created_at' => $now,
            'updated_at' => $now,
        ],
        [
            'dt_invoice' => 'CARGO-INVOICE',
            'dt_parent' => 'CARGO',
            'dt_oem' => 'MATCH',
            'fr_code' => 'CARGO',
            'dt_typec' => 'TYPE',
            'created_at' => $now,
            'updated_at' => $now,
        ],
    ]);

    $result = app(AnalogService::class)->getCargoFromAnalogs([
        ['dt_cargo' => 'MATCH', 'dt_oem' => 'MATCH', 'dt_invoice' => 'MATCH'],
    ]);

    expect($result)->toBe(['CARGO-INVOICE']);
});

it('returns product image URLs in catalog and search results', function (): void {
    Storage::fake('images');
    Storage::disk('images')->put('product-131586.jpg', 'image bytes');

    DB::table('firm')->insert([
        'fr_code' => 131586,
        'fr_name' => 'CARGO',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    $detail = Detail::factory()->create([
        'dt_code' => 131586,
        'dt_invoice' => '131586',
        'dt_typec' => 'ВИЛКА СТАРТЕРА',
        'dt_foto' => 'product-131586',
        'fr_code' => 'CARGO',
        'deleted_at' => null,
    ]);
    DB::table('oems')->insert([
        'dt_invoice' => $detail->dt_invoice,
        'dt_parent' => 'CARGO',
        'dt_oem' => 'OEM-131586',
        'fr_code' => 'CARGO',
        'dt_typec' => 'ВИЛКА СТАРТЕРА',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $expectedUrl = url('/storage/images/product-131586.jpg');

    $this->getJson('/api/v1/catalog/starter_parts/fork')
        ->assertOk()
        ->assertJsonPath('data.details.data.0.imageUrl', $expectedUrl);

    $this->getJson('/api/v1/catalog/search?searchQ=131586')
        ->assertOk()
        ->assertJsonPath('data.title', 'Поиск по 131586')
        ->assertJsonPath('data.details.data.0.imageUrl', $expectedUrl);
});

it('finds product images with an explicit extension and uppercase filename', function (): void {
    Storage::fake('images');
    Storage::disk('images')->put('PRODUCT-131586.JPEG', 'image bytes');

    $imageUrl = app(ProductImageService::class)
        ->getImageUrl('PRODUCT-131586.JPEG');

    expect($imageUrl)->toBe(url('/storage/images/PRODUCT-131586.JPEG'));
});
