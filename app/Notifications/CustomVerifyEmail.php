<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmail
{

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Verifikacija email naloga')
            ->view('CustomVerifyEmail', [
                'url' => url(route('verification.verify', [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification())
                ], false))
            ]);
    }
}
