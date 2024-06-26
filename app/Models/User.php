<?php

namespace App\Models;

use App\Models\Interfaces\UserInterface;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements UserInterface
{
    use HasApiTokens, HasFactory, Notifiable;

    /** @const string */
    const TABLE_NAME = 'users';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    // --- Model relationships

    /**
     * @return Model
     */
    public function getUserRole(): Model
    {
        return $this->belongsTo(UserRole::class, 'role_id', 'id')->first();
    }

    /**
     * @return Model|null
     */
    public function getUniversityAdmin(): ?Model
    {
       return $this->hasOne(University::class, 'admin_id', 'id')->first();
    }

    /**
     * @return Model|null
     */
    public function getTeacher() : ?Model
    {
        return $this->hasOne(Teacher::class, 'user_id', 'id')->first();
    }

    /**
     * @return Model|null
     */
    public function getStudent() : ?Model
    {
        return $this->hasOne(Student::class, 'user_id', 'id')->first();
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
    public function getRoleId(): int
    {
        return (int) $this->getAttribute('role_id');
    }

    /**
     * @return string
     */
    public function getFirstName() : string
    {
        return (string) $this->getAttribute('first_name');
    }

    /**
     * @return string
     */
    public function getLastName() : string
    {
        return (string) $this->getAttribute('last_name');
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return (string) $this->getAttribute('first_name') . ' ' . (string) $this->getAttribute('last_name');
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return (string) $this->getAttribute('email');
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return (string) $this->getAttribute('phone_number');
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return (string) $this->getAttribute('password');
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
     * @return bool
     */
    public function isTeacher(): bool
    {
        return $this->getRoleId() === UserRole::USER_ROLE_TEACHER;
    }

    /**
     * @return bool
     */
    public function isStudent(): bool
    {
        return $this->getRoleId() === UserRole::USER_ROLE_STUDENT;
    }

    /**
     * @return bool
     */
    public function isUniversityAdmin(): bool
    {
        return $this->getRoleId() === UserRole::USER_ROLE_UNIVERSITY_ADMIN;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->getRoleId() === UserRole::USER_ROLE_ADMIN;
    }
}
