<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ChangeStatus extends Mailable
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
            subject: 'Promena statusa',
        );
    }
    public function content()
    {
        return new Content(
            view: 'changeStatusMail',
        );
    }
    public function attachments()
    {
        return [];
    }
}
