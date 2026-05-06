<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Jauns užsakymas #' . $this->order->id);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-placed',
            with: ['logoBase64' => base64_encode(file_get_contents(public_path('images/logo.png')))]
        );
    }

    public function attachments(): array
    {
        if ($this->order->label_path && Storage::exists($this->order->label_path)) {
            return [
                Attachment::fromStorage($this->order->label_path)
                    ->as('label_order_' . $this->order->id . '.pdf')
                    ->withMime('application/pdf'),
            ];
        }
        return [];
    }
}
