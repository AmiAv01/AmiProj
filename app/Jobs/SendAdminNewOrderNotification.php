<?php

namespace App\Jobs;

use App\Mail\OrderCreated;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class SendAdminNewOrderNotification implements ShouldQueue
{
    use Queueable;

    protected Order $order;
    protected string $adminEmail;
    public function __construct(Order $order, ?string $adminEmail = null)
    {
        $this->order = $order;
        $this->adminEmail = $adminEmail ?? config('mail.notification_mail');
    }

    public function handle(): void
    {
        Mail::to($this->adminEmail)->send(new OrderCreated($this->order));
    }
}
