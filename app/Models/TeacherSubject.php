<?php

namespace App\Models;

use App\Models\Interfaces\TeacherSubjectInterface;
use Illuminate\Database\Eloquent\Model;

class TeacherSubject extends Model implements TeacherSubjectInterface
{
    /** @const string */
    const TABLE_NAME = 'teacher_subjects';

    /**
     * @var string[]
     */
    protected $fillable = [
        'teacher_id',
        'subject_id',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    // --- Model relationships

    /**
     * @return Model
     */
    public function getTeacher(): Model
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'user_id')->first();
    }

    /**
     * @return Model
     */
    public function getSubject(): Model
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id')->first();
    }


    // --- Model getters

    /**
     * @return int
     */
    public function getTeacherId(): int
    {
        return (int) $this->getAttribute('teacher_id');
    }

    /**
     * @return int
     */
    public function getSubjectId(): int
    {
        return (int) $this->getAttribute('subject_id');
    }
}
