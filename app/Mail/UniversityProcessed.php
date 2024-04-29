<?php

namespace App\Mail;

use App\Models\University;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UniversityProcessed extends Mailable
{
    use Queueable, SerializesModels;

    /** @var University */
    private $university;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(University $university)
    {
        $this->university = $university;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'University ' . ucfirst($this->university->getActivatedAt() ? 'approved' : 'rejected'),
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'mail.university-processed',
            with: [
                'universityName' => $this->university->getName(),
                'universityAdminName' => $this->university->getAdmin()->getFullName(),
                'status' => $this->university->getActivatedAt() ? 'approved' : 'rejected',
                'url' => route('login'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
