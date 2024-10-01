<?php

namespace App\Models;

use App\Models\Interfaces\TopicInterface;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model implements TopicInterface
{
    /** @const string */
    const TABLE_NAME = 'topics';

    /** @var string[] */
    protected $fillable = [
        'teacher_id',
        'topic',
        'is_ai_generated',
    ];

    /**
     * @return Model
     */
    public function getTeacher(): Model
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'user_id')->first();
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
    public function getTeacherId(): int
    {
        return (int) $this->getAttribute('teacher_id');
    }

    /**
     * @return string
     */
    public function getTopic(): string
    {
        return (string) $this->getAttribute('topic');
    }

    /**
     * @return int
     */
    public function getIsAiGenerated(): int
    {
        return (int) $this->getAttribute('is_ai_generated');
    }

    /**
     * @return string
     */
    public function getCreatedAt() : string
    {
        return (string) $this->getAttribute('created_at');
    }

    /**
     * @return string
     */
    public function getUpdatedAt() : string
    {
        return (string) $this->getAttribute('updated_at');
    }
}
