<?php

namespace App\Models;

use App\Models\Interfaces\GroupInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model implements GroupInterface
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'course_id',
        'title',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    // --- Model relationships

    /**
     * @return Model
     */
    public function getCourse(): Model
    {
        return $this->belongsTo(Course::class, 'course_id', 'id')->first();
    }

    /**
     * @return Collection
     */
    public function getStudents(): Collection
    {
        return $this->hasMany(Student::class, 'group_id', 'id')->get();
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
    public function getCourseId(): int
    {
        return (int) $this->getAttribute('course_id');
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return (string) $this->getAttribute('title');
    }
}
