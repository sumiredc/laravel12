<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class InitialPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        private readonly string $plainPassword
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Initial Password Mail',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.initial-password-mail',
            with: [
                'password' => $this->plainPassword,
            ]
        );
    }

    /**
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
