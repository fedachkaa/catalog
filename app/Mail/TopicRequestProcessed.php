<?php

namespace App\Mail;

use App\Models\Student;
use App\Models\TopicRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TopicRequestProcessed extends Mailable
{
    use Queueable, SerializesModels;

    /** @var string[] */
    const AVAILABLE_SUBJECTS_BY_STATUS = [
        TopicRequest::STATUS_APPROVED => 'Your topic request was approved!',
        TopicRequest::STATUS_REJECTED => 'Your topic request was rejected.',
    ];

    /** @var TopicRequest */
    private $topicRequest;

    /** @var Student */
    private $student;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(TopicRequest $topicRequest, Student $student)
    {
        $this->topicRequest = $topicRequest;
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
            subject: self::AVAILABLE_SUBJECTS_BY_STATUS[$this->topicRequest->getStatus()],
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
            markdown: 'mail.topic-processed',
            with: [
                'studentFullName' => $this->student->getUser()->getFullName(),
                'topic' => $this->topicRequest->getTopic()->getTopic(),
                'status' => $this->topicRequest->getStatus(),
                'catalogType' => \App\Models\Catalog::AVAILABLE_CATALOG_TYPES[$this->topicRequest->getTopic()->getCatalog()->getType()],
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
