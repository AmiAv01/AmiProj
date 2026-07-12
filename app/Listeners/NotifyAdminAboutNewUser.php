<?php

namespace App\Listeners;

use App\Jobs\SendAdminNewUserNotification;
use App\Models\User;
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
        if (! $event->user instanceof User) {
            return;
        }

        SendAdminNewUserNotification::dispatch($event->user);
    }
}
