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

    /** @var string[] */
    private const AVAILABLE_CONTENT_BY_STATUS = [
        'approved' => 'Університет %%UNIVERSITY_NAME%% було %%STATUS%%. Скористайтесь скиданням паролю для створення нового та авторизації в системі для доступу до подальшого налаштуванням університету.',
        'rejected' => 'Університет %%UNIVERSITY_NAME%% було %%STATUS%%. У разі питань звертайтесь за електронною поштою admin@unispace.com.',
    ];

    /** @var string[] */
    private const AVAILABLE_STATUSES = [
        'approved' => 'схвалено',
        'rejected' => 'відхилено',
    ];

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
            subject: 'Університет ' . ucfirst(self::AVAILABLE_STATUSES[$this->university->getActivatedAt() ? 'approved' : 'rejected']),
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        $status = $this->university->getActivatedAt() ? 'approved' : 'rejected';
        return new Content(
            markdown: 'mail.university-processed',
            with: [
                'universityAdminName' => $this->university->getAdmin()->getFullName(),
                'status' => $status,
                'message' => $this->getMessage($status),
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

    /**
     * @param string $status
     * @return string
     */
    private function getMessage(string $status): string
    {
        $message = self::AVAILABLE_CONTENT_BY_STATUS[$status];

        $message = str_replace('%%UNIVERSITY_NAME%%', $this->university->getName(), $message);

        return str_replace('%%STATUS%%', self::AVAILABLE_STATUSES[$status], $message);
    }
}
