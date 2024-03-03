<?php

namespace App\Models;

use App\Models\Interfaces\UserRoleInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model implements UserRoleInterface
{
    use HasFactory;

    /** @const int  */
    const USER_ROLE_ADMIN = 1;
    const USER_ROLE_UNIVERSITY_ADMIN = 2;
    const USER_ROLE_TEACHER = 3;
    const USER_ROLE_STUDENT = 4;

    /** @const string[] */
    const AVAILABLE_USER_ROLES = [
        self::USER_ROLE_ADMIN => 'Адміністратор',
        self::USER_ROLE_UNIVERSITY_ADMIN => 'Адміністратор університету',
        self::USER_ROLE_TEACHER => 'Викладач',
        self::USER_ROLE_STUDENT => 'Студент',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;


    // --- Model relationships

    /**
     * @return Collection
     */
    public function getUsers(): Collection
    {
        return $this->hasMany(User::class)->get();
    }


    // -- Model getters

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
    public function getRole(): string
    {
        return (string) $this->getAttribute('role');
    }
}
