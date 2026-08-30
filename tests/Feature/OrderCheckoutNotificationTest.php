<?php

use App\Enums\OrderStatus;
use App\Jobs\SendAdminNewOrderNotification;
use App\Mail\OrderCreated;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Detail;
use App\Models\Order;
use App\Models\User;
use App\Services\CurrencyService;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

function createOrderNotificationDetail(array $overrides = []): Detail
{
    return Detail::factory()->create(array_merge([
        'dt_code' => 6303,
        'dt_invoice' => '6303DDUC3E',
        'dt_typec' => 'ПОДШИПНИК',
        'deleted_at' => null,
    ], $overrides));
}

it('stores the checkout comment and queues the admin email', function (): void {
    Queue::fake();

    $user = User::factory()->create(['approved' => true]);
    $detail = createOrderNotificationDetail();
    $cart = Cart::create(['user_id' => $user->id]);
    CartItem::create([
        'cart_id' => $cart->id,
        'dt_id' => $detail->dt_id,
        'quantity' => 2,
        'price' => '15.89',
    ]);

    $this->actingAs($user)
        ->postJson('/api/v1/orders', ['comment' => 'Оплата по безналу'])
        ->assertCreated()
        ->assertJsonPath('data.comment', 'Оплата по безналу')
        ->assertJsonPath('data.total_price', '31.78');

    $this->assertDatabaseHas('order', [
        'created_by' => $user->id,
        'comment' => 'Оплата по безналу',
        'total_price' => 31.78,
    ]);
    $this->assertDatabaseMissing('cart_item', ['cart_id' => $cart->id]);
    Queue::assertPushed(SendAdminNewOrderNotification::class);
});

it('sends a detailed order email to the configured notification recipients', function (): void {
    Mail::fake();
    config(['mail.notification_mail' => 'orders@example.com, manager@example.com']);

    $user = User::factory()->create([
        'name' => 'ИП Пичугин (Минск)',
        'email' => 'client@example.com',
        'phone_number' => '+375291234567',
    ]);
    $detail = createOrderNotificationDetail();
    DB::table('stk')->insert([
        'code' => $detail->dt_code,
        'ostc' => '34',
        'ost' => '34',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    DB::table('currency')->insert([
        'code' => 'EUR',
        'value' => Crypt::encrypt('3.50'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    $order = Order::create([
        'total_price' => '317.80',
        'status' => OrderStatus::NEW->value,
        'comment' => 'Оплата по безналу',
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);
    $order->orderItems()->create([
        'detail_id' => $detail->dt_id,
        'quantity' => 20,
        'unit_price' => '15.89',
    ]);

    (new SendAdminNewOrderNotification($order))->handle(app(CurrencyService::class));

    Mail::assertSent(OrderCreated::class, function (OrderCreated $mail): bool {
        $html = $mail->render();

        return $mail->hasTo('orders@example.com')
            && $mail->hasTo('manager@example.com')
            && str_contains($html, 'ИП Пичугин (Минск)')
            && str_contains($html, 'Курс пересчёта 3,50')
            && str_contains($html, '6303DDUC3E')
            && str_contains($html, '15,89')
            && str_contains($html, '34')
            && str_contains($html, 'Оплата по безналу');
    });
});
