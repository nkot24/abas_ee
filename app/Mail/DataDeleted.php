<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DataDeleted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public int $orderCount) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your personal data has been deleted / Jūsų duomenys ištrinti');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.data-deleted');
    }

    public function attachments(): array
    {
        return [];
    }
}
