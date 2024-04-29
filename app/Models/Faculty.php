<?php

namespace App\Models;

use App\Models\Interfaces\FacultyInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model implements FacultyInterface
{
    use HasFactory;

    /** @const string */
    const TABLE_NAME = 'faculties';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'university_id',
        'title',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;


    // --- Model relationships

    /**
     * @return Collection
     */
    public function getCourses(): Collection
    {
        return $this->hasMany(Course::class, 'faculty_id', 'id')->get();
    }

    /**
     * @return Model
     */
    public function getUniversity(): Model
    {
        return $this->belongsTo(University::class, 'university_id', 'id')->first();
    }

    /**
     * @return Collection
     */
    public function getTeachers(): Collection
    {
        return $this->hasMany(Teacher::class, 'faculty_id', 'id')->get();
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
