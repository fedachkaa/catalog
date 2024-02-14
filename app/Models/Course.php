<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'faculty_id',
        'course'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    // --- Model relationships

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
    public function getGroups(): Collection
    {
        return $this->hasMany(Group::class, 'course_id', 'id')->get();
    }

    /**
     * @return Collection
     */
    public function getStudents(): Collection
    {
        return $this->hasMany(Student::class, 'course_id', 'id')->get();
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
    public function getFacultyId(): int
    {
        return (int) $this->getAttribute('faculty_id');
    }

    /**
     * @return int
     */
    public function getCourse(): int
    {
        return (int) $this->getAttribute('course');
    }
}
