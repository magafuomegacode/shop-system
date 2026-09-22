<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $systemName;

    public function __construct($user, string $systemName)
    {
        $this->user = $user;
        $this->systemName = $systemName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '👋 Karibu ' . $this->systemName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
        );
    }
}