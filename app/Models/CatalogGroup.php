<?php

namespace App\Models;

use App\Models\Interfaces\CatalogGroupInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogGroup extends Model implements CatalogGroupInterface
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'catalog_id',
        'group_id',
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
    public function getGroup(): Model
    {
        return $this->belongsTo(Group::class, 'group_id', 'id')->first();
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
    public function getGroupId(): int
    {
        return (int) $this->getAttribute('group_id');
    }
}
