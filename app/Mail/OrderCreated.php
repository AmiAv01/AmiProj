<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCreated extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Order $order,
        public ?string $currencyRate = null,
    ) {}

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
                'currencyRate' => $this->currencyRate,
                'currencyCode' => config('currency.display_code'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
