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
      self::TYPE_CATALOG_COURSE_WORK => 'Курсова робота',
      self::TYPE_CATALOG_DIPLOMA_WORK => 'Дипломна робота',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'type',
        'is_active',
        'created_at',
        'updated_at',
        'activated_at',
    ];

    // --- Model relationships

    /**
     * @return Collection
     */
    public function getGroups(): Collection
    {
        return $this->hasMany(CatalogGroup::class, 'catalog_id', 'id')->get();
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
     * @return string
     */
    public function getType(): string
    {
        return (string) $this->getAttribute('type');
    }

    /**
     * @return int
     */
    public function getIsActive(): int
    {
        return (int) $this->getAttribute('is_active');
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

    /**
     * @return string
     */
    public function getActivatedAt(): string
    {
        return (string) $this->getAttribute('activated_at');
    }
}
