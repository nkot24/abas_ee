<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Užsakymo patvirtinimas #' . $this->order->id);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-confirmed',
            with: ['logoBase64' => base64_encode(file_get_contents(public_path('images/logo.png')))]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
