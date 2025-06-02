<?php
namespace App\Mail;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Order $order;
    public $siteSettings;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $keys = ['site_name', 'site_logo',];
        $this->siteSettings = Setting::whereIn('key', $keys)->pluck('value', 'key')->all();
    }

    public function envelope(): Envelope
    {
        $appName = $this->siteSettings['site_name'] ?? config('app.name');
        return new Envelope(
            subject: 'Pengingat Pembayaran untuk Pesanan Anda #' . $this->order->id . ' di ' . $appName,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.orders.payment-reminder',
            with: [
                'orderUrl' => route('dashboard.orders.show', $this->order->id),
            ],
        );
    }
}