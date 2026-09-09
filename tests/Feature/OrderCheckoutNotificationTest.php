<?php

use App\Enums\OrderStatus;
use App\Jobs\SendAdminNewOrderNotification;
use App\Mail\OrderConfirmed;
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
use Illuminate\Support\Facades\URL;

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
    DB::table('currency')->where('code', 'EUR')->update([
        'value' => Crypt::encrypt('3.50'),
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
            && str_contains($html, 'Оплата по безналу')
            && str_contains($html, 'Подтвердить заказ')
            && str_contains($html, '/orders/'.$mail->order->id.'/confirm');
    });
});

it('confirms an order through a signed email link and notifies the customer email', function (): void {
    Mail::fake();

    $user = User::factory()->create([
        'email' => 'login@example.com',
        'notification_email' => 'purchases@example.com',
    ]);
    $order = Order::create([
        'total_price' => '25.00',
        'status' => OrderStatus::NEW->value,
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);
    $confirmationUrl = URL::temporarySignedRoute(
        'orders.confirm',
        now()->addDays(7),
        ['order' => $order->id],
    );

    $this->get($confirmationUrl)
        ->assertOk()
        ->assertSee("Заказ №{$order->order_number}")
        ->assertSee('Клиенту отправлено уведомление.');

    $this->assertDatabaseHas('order', [
        'id' => $order->id,
        'status' => OrderStatus::DONE->value,
    ]);
    Mail::assertSent(OrderConfirmed::class, function (OrderConfirmed $mail): bool {
        return $mail->hasTo('purchases@example.com')
            && str_contains($mail->render(), $mail->order->order_number);
    });
});

it('does not notify the customer twice when the confirmation link is reopened', function (): void {
    Mail::fake();

    $user = User::factory()->create(['notification_email' => 'customer@example.com']);
    $order = Order::create([
        'total_price' => '25.00',
        'status' => OrderStatus::NEW->value,
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);
    $confirmationUrl = URL::temporarySignedRoute(
        'orders.confirm',
        now()->addDays(7),
        ['order' => $order->id],
    );

    $this->get($confirmationUrl)->assertOk();
    $this->get($confirmationUrl)
        ->assertOk()
        ->assertSee('Этот заказ уже был подтверждён.');

    Mail::assertSent(OrderConfirmed::class, 1);
});

it('rejects a tampered order confirmation link', function (): void {
    Mail::fake();

    $user = User::factory()->create();
    $order = Order::create([
        'total_price' => '25.00',
        'status' => OrderStatus::NEW->value,
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);

    $this->get("/orders/{$order->id}/confirm?expires=1&signature=invalid")
        ->assertForbidden();

    $this->assertDatabaseHas('order', [
        'id' => $order->id,
        'status' => OrderStatus::NEW->value,
    ]);
    Mail::assertNothingSent();
});

it('lets an administrator change the customer notification email', function (): void {
    $admin = User::factory()->create(['isAdmin' => true]);
    $customer = User::factory()->create([
        'email' => 'login@example.com',
        'notification_email' => 'old@example.com',
    ]);

    $this->actingAs($admin)
        ->putJson("/api/v1/admin/users/{$customer->id}", [
            'notification_email' => 'NEW-NOTIFICATIONS@EXAMPLE.COM ',
        ])
        ->assertOk()
        ->assertJsonPath('data.success', true);

    $this->assertDatabaseHas('user', [
        'id' => $customer->id,
        'email' => 'login@example.com',
        'notification_email' => 'new-notifications@example.com',
    ]);
});
