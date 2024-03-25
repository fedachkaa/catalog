<?php

namespace App\Models;

use App\Models\Interfaces\CatalogInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalog extends Model implements CatalogInterface
{
    use HasFactory;

    /** @const string */
    const TYPE_CATALOG_COURSE_WORK = 'course_work';
    const TYPE_CATALOG_DIPLOMA_WORK = 'diploma_work';

    /** @const string[] */
    const AVAILABLE_CATALOG_TYPES = [
      self::TYPE_CATALOG_COURSE_WORK => 'Course work',
      self::TYPE_CATALOG_DIPLOMA_WORK => 'Diploma work',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'group_id',
        'type'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    // --- Model relationships

    /**
     * @return Model
     */
    public function getGroup(): Model
    {
        return $this->belongsTo(Group::class, 'group_id', 'id')->first();
    }

    /**
     * @return Collection
     */
    public function getTopics(): Collection
    {
        return $this->hasMany(CatalogTopic::class, 'catalog_id', 'id')->get();
    }

    /**
     * @return Collection
     */
    public function getSupervisors(): Collection
    {
        return $this->hasMany(CatalogSupervisor::class, 'catalog_id', 'id')->get();
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
    public function getGroupId(): int
    {
        return (int) $this->getAttribute('group_id');
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return (string) $this->getAttribute('type');
    }
}
