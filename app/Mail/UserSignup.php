<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserSignup extends Mailable
{
    use Queueable, SerializesModels;

    /** @var User */
    private $user;

    /** @var string */
    private $password;

    /**
     * @param User $user
     * @param string $password
     */
    public function __construct(User $user, string $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Signup success!',
        );
    }

    /**
     * @return Content
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.auth.signup',
            with: [
                'user' => $this->user->toArray(),
                'password' => $this->password,
                'url' => route('login'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
