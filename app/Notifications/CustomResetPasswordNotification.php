<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use App\Models\Setting;

class CustomResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $token;
    public static $toMailCallback;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
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
        $siteNameSetting = Setting::where('key', 'site_name')->first();
        $appName = $siteNameSetting ? $siteNameSetting->value : config('app.name', 'Catering Lezat');

        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject(Lang::get('Notifikasi Atur Ulang Password') . ' - ' . $appName)
            ->line(Lang::get('Anda menerima email ini karena kami menerima permintaan untuk mengatur ulang password akun Anda.'))
            ->action(Lang::get('Atur Ulang Password'), $resetUrl)
            ->line(Lang::get('Link atur ulang password ini akan kedaluwarsa dalam :count menit.', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]))
            ->line(Lang::get('Jika Anda tidak meminta untuk mengatur ulang password, abaikan email ini.'));
    }

    /**
     * Set a callback that should be used when building the mail message.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
