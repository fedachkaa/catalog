<?php

namespace App\Models;

use App\Models\Interfaces\TeacherInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model implements TeacherInterface
{
    /** @const string */
    const TABLE_NAME = 'teachers';

    /** @var array */
    protected $fillable = [
        'user_id',
        'faculty_id',
    ];

    // --- Model relationships

    /**
     * @return User|Model
     */
    public function getUser(): User
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->first();
    }

    /**
     * @return Model
     */
    public function getFaculty(): Model
    {
        return $this->belongsTo(Faculty::class, 'faculty_id', 'id')->first();
    }

    /**
     * @return Collection
     */
    public function getSubjects(): Collection
    {
        return $this->belongsToMany(Subject::class, 'teacher_subjects', 'teacher_id', 'subject_id', 'user_id', 'id')->get();
    }

    // -- Model getters

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return (int) $this->getAttribute('user_id');
    }

    /**
     * @return int
     */
    public function getFacultyId(): int
    {
        return (int) $this->getAttribute('faculty_id');
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
