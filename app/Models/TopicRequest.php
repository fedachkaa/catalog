<?php

namespace App\Models;

use App\Models\Interfaces\TopicRequestInterface;
use Illuminate\Database\Eloquent\Model;

class TopicRequest extends Model implements TopicRequestInterface
{
    /** @const string */
    const TABLE_NAME = 'topic_requests';

    /** @var int */
    const MAX_TOPIC_REQUESTS_PER_STUDENT = 3;

    /** @const string */
    const STATUS_SENT = 'sent';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    /** @var array */
    const AVAILABLE_STATUSES = [
        self::STATUS_SENT => 'Надіслано',
        self::STATUS_APPROVED => 'Підтверджено',
        self::STATUS_REJECTED => 'Відхилено',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'topic_id',
        'student_id',
        'status',
    ];

    // --- Model relationships

    /**
     * @return Model|CatalogTopic
     */
    public function getCatalogTopic(): Model|CatalogTopic
    {
        return $this->belongsTo(CatalogTopic::class, 'topic_id', 'id')->first();
    }

    /**
     * @return Model
     */
    public function getStudent(): Model
    {
        return $this->belongsTo(Student::class, 'student_id', 'user_id')->first();
    }

    // --- Model getters

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int) $this->getAttribute('id');
    }

    /**
     * @return int
     */
    public function getTopicId(): int
    {
        return (int) $this->getAttribute('topic_id');
    }

    /**
     * @return int
     */
    public function getStudentId(): int
    {
        return (int) $this->getAttribute('student_id');
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return (string) $this->getAttribute('status');
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return (string) $this->getAttribute('created_at');
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return (string) $this->getAttribute('updated_at');
    }
}
