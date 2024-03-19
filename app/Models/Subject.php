<?php

namespace App\Models;

use App\Models\Interfaces\SubjectInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model implements SubjectInterface
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'university_id',
        'title',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function getTeachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_subjects', 'subject_id', 'teacher_id', 'id', 'user_id');
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
    public function getUniversityId(): int
    {
        return (int) $this->getAttribute('university_id');
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return (string) $this->getAttribute('title');
    }
}
