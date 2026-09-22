<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $systemName;
    public $systemPhone;
    public $systemEmail;
    public $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($user, string $password, array $settings = [])
    {
        $this->user = $user;
        $this->password = $password;
        $this->systemName = $settings['system_name'] ?? config('app.name', 'Duka System');
        $this->systemPhone = $settings['phone'] ?? '';
        $this->systemEmail = $settings['email'] ?? '';
        $this->loginUrl = route('login');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔐 Your ' . $this->systemName . ' Account Credentials',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.user-credentials',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}