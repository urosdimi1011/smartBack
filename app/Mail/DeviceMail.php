<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DeviceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $device;

    public function __construct($device)
    {
        $this->device = $device;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Informacije o uredjaju',
        );
    }


    public function content()
    {
        return new Content(
            view: 'deviceMail',
        );
    }

    public function attachments()
    {
        return [];
    }
}
