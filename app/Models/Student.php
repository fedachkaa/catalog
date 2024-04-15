<?php

namespace App\Models;

use App\Models\Interfaces\StudentInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model implements StudentInterface
{
    use HasFactory;

    /** @const string */
    const TABLE_NAME = 'students';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'group_id',
    ];

    // --- Model relationships

    /**
     * @return Model
     */
    public function getUser(): Model
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->first();
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
    public function getUserId(): int
    {
        return (int) $this->getAttribute('user_id');
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
}
