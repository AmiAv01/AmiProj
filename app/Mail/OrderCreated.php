<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderCreated extends Mailable
{
    use Queueable, SerializesModels;

    protected Order $order;
    public function __construct(Order $order)
    {
        $this->order = $order;
        Log::info($order);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $currentDateTime = now()->translatedFormat('j F Y H:i');
        return new Envelope(
            subject: "АМИ-АВТО Новый заказ от " . $currentDateTime,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.order_created',
            with: [
                'order' => $this->order,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
