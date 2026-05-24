<?php

namespace App\Jobs;

use App\Mail\OrderCreated;
use App\Mail\UserApproved;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAdminApproveUserNotification implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    protected User $user;
    protected string $adminEmail;
    public function __construct(User $user, ?string $adminEmail = null)
    {
        $this->user = $user;
        $this->adminEmail = $adminEmail ?? config('mail.notification_mail');
    }

    public function handle(): void
    {
        Mail::to($this->adminEmail)->send(new UserApproved($this->user));
    }
}
