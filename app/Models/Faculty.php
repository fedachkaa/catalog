<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    use HasFactory;

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
     * @return HasMany
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'faculty_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class, 'university_id', 'id');
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
