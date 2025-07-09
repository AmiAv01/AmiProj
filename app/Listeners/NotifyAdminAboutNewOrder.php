<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Jobs\SendAdminNewUserNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NotifyAdminAboutNewOrder
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        Log::info($event->order);
        $orderItems = $event->order->orderItems;
        Log::info($event->order->user);
        foreach ($orderItems as $item) {
            Log::info($item->detail);
        }

        SendAdminNewUserNotification::dispatch($event->order);
    }
}
