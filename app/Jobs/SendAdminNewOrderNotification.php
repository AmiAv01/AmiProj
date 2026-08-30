<?php

namespace App\Jobs;

use App\Mail\OrderCreated;
use App\Models\Order;
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

    public function handle(): void
    {
        $emails = $this->adminEmail;

        if (is_string($emails)) {
            $emails = array_filter(array_map('trim', explode(',', $emails)));
        }

        if (empty($emails)) {
            Log::warning('SendAdminNewOrderNotification: no admin recipients configured for new order '.$this->order->id);

            return;
        }

        Mail::to($emails)->send(new OrderCreated($this->order));
    }

    public function failed(Throwable $exception): void
    {
        Log::error('New order notification job failed.', [
            'order_id' => $this->order->getKey(),
            'exception' => $exception::class,
        ]);
    }
}
