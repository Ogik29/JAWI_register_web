<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL; // <-- WAJIB: Tambahkan ini
use Illuminate\Support\Carbon; // <-- WAJIB: Tambahkan ini

class VerifyEmailWithStatus extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        // Membuat URL verifikasi yang aman dan berlaku selama 60 menit
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifikasi Alamat Email Anda')
            ->line('Terima kasih telah mendaftar! Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda.')
            ->action('Verifikasi Email', $verificationUrl)
            ->line('Jika Anda tidak membuat akun ini, abaikan email ini.');
    }

    /**
     * Membuat URL verifikasi yang ditandatangani (signed).
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl(object $notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify-custom', // Nama route yang akan kita buat nanti
            Carbon::now()->addMinutes(60), // URL berlaku selama 60 menit
            [
                'id' => $notifiable->getKey(), // Mengirim ID user
            ]
        );
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
