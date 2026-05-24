<?php

namespace App\Listeners;

use App\Jobs\SendAdminNewUserNotification;
use Illuminate\Auth\Events\Registered;

class NotifyAdminAboutNewUser
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
    public function handle(Registered $event): void
    {
        SendAdminNewUserNotification::dispatch($event->user);
    }
}
