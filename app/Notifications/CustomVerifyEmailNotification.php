<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;
use App\Models\Setting;

class CustomVerifyEmailNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        // Ambil nama situs dari database settings
        $siteNameSetting = Setting::where('key', 'site_name')->first();
        $appName = $siteNameSetting ? $siteNameSetting->value : config('app.name', 'Catering Lezat');

        return (new MailMessage)
            ->subject(Lang::get('Verifikasi Alamat Email Anda') . ' - ' . $appName)
            ->line(Lang::get('Terima kasih telah mendaftar di :app_name! Mohon klik tombol di bawah ini untuk memverifikasi alamat email Anda.', ['app_name' => $appName]))
            ->action(Lang::get('Verifikasi Alamat Email'), $verificationUrl)
            ->line(Lang::get('Jika Anda merasa tidak pernah membuat akun ini, Anda dapat mengabaikan email ini.'));
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl(object $notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
