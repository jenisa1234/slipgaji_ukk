<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Kode Verifikasi Reset Password')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Berikut adalah kode verifikasi untuk mereset password Anda:')
            ->line('**' . $this->token . '**')
            ->line('Masukkan kode ini pada pop-up reset password di aplikasi.');
    }
}