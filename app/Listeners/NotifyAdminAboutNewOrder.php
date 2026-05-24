<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Jobs\SendAdminNewOrderNotification;

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
        SendAdminNewOrderNotification::dispatch($event->order);
    }
}
