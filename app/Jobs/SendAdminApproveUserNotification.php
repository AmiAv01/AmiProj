<?php

namespace App\Jobs;

use App\Mail\UserApproved;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendAdminApproveUserNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 45;

    public bool $failOnTimeout = true;

    /** @var array<int, int> */
    public array $backoff = [10, 30, 60];

    protected User $user;

    protected string $adminEmail;

    public function __construct(User $user, ?string $adminEmail = null)
    {
        $this->user = $user;
        $this->adminEmail = $adminEmail ?? config('mail.notification_mail') ?? config('mail.from.address', 'hello@example.com');
    }

    public function handle(): void
    {
        Mail::to($this->adminEmail)->send(new UserApproved($this->user));
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Approval notification job failed.', [
            'user_id' => $this->user->getKey(),
            'exception' => $exception::class,
        ]);
    }
}
