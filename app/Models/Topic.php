<?php

namespace App\Models;

use App\Models\Interfaces\TopicInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model implements TopicInterface
{
    /** @const string */
    const TABLE_NAME = 'topics';

    /** @var string[] */
    protected $fillable = [
        'teacher_id',
        'topic',
        'keyword',
        'is_ai_generated',
    ];

    /**
     * @return Model
     */
    public function getTeacher(): Model
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'user_id')->first();
    }

    /**
     * @return Collection
     */
    public function getStudentRequests(): Collection
    {
        return $this->hasMany(TopicRequest::class, 'topic_id', 'id')->get();
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
     * @return string
     */
    public function getKeyword(): string
    {
        return (string) $this->getAttribute('keyword');
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
