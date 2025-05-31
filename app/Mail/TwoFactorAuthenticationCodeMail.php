<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Setting;

class TwoFactorAuthenticationCodeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $twoFactorCode;
    public $appName;

    /**
     * Create a new message instance.
     */
    public function __construct($twoFactorCode)
    {
        $this->twoFactorCode = $twoFactorCode;
        $siteNameSetting = Setting::where('key', 'site_name')->first();
        $this->appName = $siteNameSetting ? $siteNameSetting->value : config('app.name', 'Catering Lezat');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode Verifikasi Dua Langkah (2FA) - ' . $this->appName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.auth.2fa-code',
            with: [
                'code' => $this->twoFactorCode,
                'appName' => $this->appName,
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
