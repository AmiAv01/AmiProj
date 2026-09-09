<?php

namespace App\Jobs;

use App\Mail\OrderConfirmed;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendCustomerOrderConfirmedNotification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 45;

    public bool $failOnTimeout = true;

    /** @var array<int, int> */
    public array $backoff = [10, 30, 60];

    public function __construct(protected Order $order) {}

    public function handle(): void
    {
        $this->order->loadMissing('user');
        $recipient = $this->order->user?->notification_email;

        if (! $recipient) {
            Log::warning('Customer order confirmation was not sent: no notification email.', [
                'order_id' => $this->order->getKey(),
            ]);

            return;
        }

        Mail::to($recipient)->send(new OrderConfirmed($this->order));
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Customer order confirmation job failed.', [
            'order_id' => $this->order->getKey(),
            'exception' => $exception::class,
        ]);
    }
}
