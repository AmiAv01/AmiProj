<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Jobs\SendAdminNewOrderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NotifyAdminAboutNewOrder
{
    use InteractsWithQueue;

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
        SendAdminNewOrderNotification::dispatch($event->order);
    }
}
