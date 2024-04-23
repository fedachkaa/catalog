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

    /**
     * @param User $user
     */

    public function __construct(User $user)
    {
        $this->user = $user;
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
