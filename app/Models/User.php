<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
     * @return BelongsTo
     */
    public function userRole(): BelongsTo
    {
        return $this->belongsTo(UserRole::class, 'role_id', 'id');
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
    public function getEmail(): string
    {
        return (string) $this->getAttribute('email');
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return (string) $this->getAttribute('password');
    }
}
