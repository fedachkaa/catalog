<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserRole extends Model
{
    use HasFactory;

    /** @const int  */
    const USER_ROLE_ADMIN = 1;
    const USER_ROLE_UNIVERSITY_ADMIN = 2;
    const USER_ROLE_TEACHER = 3;
    const USER_ROLE_STUDENT = 4;

    /** @var int */
    public $id;

    /** @var string */
    public $role;


    // --- Model relationships

    /**
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->HasMany(User::class);
    }


    // -- Model getters

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int) $this->id;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return (string) $this->role;
    }
}
