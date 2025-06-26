<?php

namespace App\Jobs;

use App\Mail\UserRegistered;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendAdminNewUserNotification implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    protected User $user;
    protected string $adminEmail;
    public function __construct(User $user, string $adminEmail = 'amiavproject@gmail.com')
    {
        $this->user = $user;
        $this->adminEmail = $adminEmail;
    }

    public function handle(): void
    {
        var_dump('email not');
        Mail::to($this->adminEmail)->send(new UserRegistered($this->user));
    }
}
