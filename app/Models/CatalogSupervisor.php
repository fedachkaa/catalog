<?php

namespace App\Models;

use App\Models\Interfaces\CatalogSupervisorInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogSupervisor extends Model implements CatalogSupervisorInterface
{
    use HasFactory;

    /** @const string */
    const TABLE_NAME = 'catalog_supervisors';

    /**
     * @var string[]
     */
    protected $fillable = [
        'catalog_id',
        'teacher_id',
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
    public function getCatalogId(): int
    {
        return (int) $this->getAttribute('catalog_id');
    }

    /**
     * @return int
     */
    public function getTeacherId(): int
    {
        return (int) $this->getAttribute('teacher_id');
    }
}
