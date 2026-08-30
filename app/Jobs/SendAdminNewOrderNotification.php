<?php

namespace App\Jobs;

use App\Mail\OrderCreated;
use App\Models\Order;
use App\Services\CurrencyService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendAdminNewOrderNotification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 45;

    public bool $failOnTimeout = true;

    /** @var array<int, int> */
    public array $backoff = [10, 30, 60];

    protected Order $order;

    /**
     * @var string|array|null
     */
    protected $adminEmail;

    public function __construct(Order $order, $adminEmail = null)
    {
        $this->order = $order;
        $this->adminEmail = $adminEmail ?? config('mail.notification_mail');
    }

    public function handle(CurrencyService $currencies): void
    {
        $emails = $this->adminEmail;

        if (is_string($emails)) {
            $emails = array_filter(array_map('trim', explode(',', $emails)));
        }

        if (empty($emails)) {
            Log::warning('SendAdminNewOrderNotification: no admin recipients configured for new order '.$this->order->id);

            return;
        }

        $this->order->loadMissing([
            'user',
            'orderItems.detail.stock',
        ]);

        $currencyRate = null;
        try {
            $currencyRate = $currencies->getCurrency();
        } catch (Throwable $exception) {
            Log::warning('New order email will be sent without the currency rate.', [
                'order_id' => $this->order->getKey(),
                'exception' => $exception::class,
            ]);
        }

        Mail::to($emails)->send(new OrderCreated($this->order, $currencyRate));
    }

    public function failed(Throwable $exception): void
    {
        Log::error('New order notification job failed.', [
            'order_id' => $this->order->getKey(),
            'exception' => $exception::class,
        ]);
    }
}
