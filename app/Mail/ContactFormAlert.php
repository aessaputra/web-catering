<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class ContactFormAlert extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $contactData;

    /**
     * Create a new message instance.
     */
    public function __construct(array $contactData)
    {
        $this->contactData = $contactData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $fromEmail = $this->contactData['email'];
        $fromName = $this->contactData['name'];
        $appName = config('app.name');

        return new Envelope(
            from: new Address($fromEmail, $fromName . ' (via ' . $appName . ' - Kontak)'),
            replyTo: [new Address($fromEmail, $fromName)],
            subject: 'Pesan Kontak Baru dari Website: ' . $appName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact.form-message',
            with: [
                'name' => $this->contactData['name'],
                'email' => $this->contactData['email'],
                'messageContent' => $this->contactData['message'],
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
