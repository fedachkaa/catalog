<?php

namespace App\Models;

use App\Models\Interfaces\CatalogTopicInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogTopic extends Model implements CatalogTopicInterface
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'catalog_id',
        'teacher_id',
        'topic',
        'student_id',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    // --- Model relationships

    /**
     * @return Model
     */
    public function getCatalog(): Model
    {
        return $this->belongsTo(Catalog::class, 'catalog_id', 'id')->first();
    }

    /**
     * @return Model|null
     */
    public function getStudent(): ?Model
    {
        return $this->belongsTo(Student::class, 'student_id', 'user_id')->first();
    }

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
    public function getCatalogId(): int
    {
        return (int) $this->getAttribute('catalog_id');
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
    public function getStudentId(): int
    {
        return (int)$this->getAttribute('student_id');
    }
}
