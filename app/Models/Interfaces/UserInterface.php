<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface UserInterface
{
    public function getUserRole(): Model;

    public function getUniversityAdmin(): ?Model;

    public function getId(): int;

    public function getRoleId(): int;

    public function getFirstName() : string;

    public function getLastName() : string;

    public function getFullName(): string;

    public function getEmail(): string;

    public function getPhoneNumber(): string;

    public function getPassword(): string;

    public function getCreatedAt(): string;

    public function getUpdatedAt(): string;

    public function isTeacher(): bool;

    public function isStudent(): bool;

    public function isUniversityAdmin(): bool;

    public function isAdmin(): bool;
}
