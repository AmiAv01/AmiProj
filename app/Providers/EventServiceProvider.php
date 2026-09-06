<?php

namespace App\Providers;

use App\Events\OrderCreated;
use App\Listeners\NotifyAdminAboutNewOrder;
use App\Listeners\NotifyAdminAboutNewUser;
use App\Listeners\QueueEmailVerificationNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            QueueEmailVerificationNotification::class,
            NotifyAdminAboutNewUser::class,
        ],
        OrderCreated::class => [
            NotifyAdminAboutNewOrder::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
