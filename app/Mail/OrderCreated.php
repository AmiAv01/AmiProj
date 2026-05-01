<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderCreated extends Mailable
{
    use Queueable;
    use SerializesModels;

    protected Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
        Log::info($order);
    }

    public function envelope(): Envelope
    {
        $currentDateTime = now()->translatedFormat('j F Y H:i');

        return new Envelope(
            subject: __('ami_auto_new_order_from_date', ['date' => $currentDateTime]),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'email.order_created',
            with: [
                'order' => $this->order,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
