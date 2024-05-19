<?php

namespace App\Mail;

use App\Models\Catalog;
use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CatalogActivation extends Mailable
{
    use Queueable, SerializesModels;

    /** @var Catalog */
    private $catalog;

    /** @var Student */
    private $student;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Catalog $catalog, Student $student)
    {
        $this->catalog = $catalog;
        $this->student = $student;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Каталог активовано!',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        $university = $this->student->getGroup()->getCourse()->getFaculty()->getUniversity();

        return new Content(
            markdown: 'mail.catalog-activation',
            with: [
                'studentFullName' => $this->student->getUser()->getFullName(),
                'catalogType' => \App\Models\Catalog::AVAILABLE_CATALOG_TYPES[$this->catalog->getType()],
                'url' => route('get.catalog', [
                    'universityId' => $university->getId(),
                    'catalogId' => $this->catalog->getId(),
                ]),
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
