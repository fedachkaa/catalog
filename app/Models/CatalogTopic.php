<?php

namespace App\Models;

use App\Models\Interfaces\CatalogTopicInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogTopic extends Model implements CatalogTopicInterface
{
    use HasFactory;

    /** @const string */
    const TABLE_NAME = 'catalog_topics';

    /**
     * @var string[]
     */
    protected $fillable = [
        'catalog_id',
        'student_id',
        'topic_id'
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
     * @return Model|Topic
     */
    public function getTopic(): Model|Topic
    {
        return $this->belongsTo(Topic::class, 'topic_id', 'id')->first();
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
    public function getCatalogId(): int
    {
        return (int) $this->getAttribute('catalog_id');
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
        return (int)$this->getAttribute('student_id');
    }
}
