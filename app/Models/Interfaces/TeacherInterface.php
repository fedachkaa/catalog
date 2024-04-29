<?php

namespace App\Models\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface TeacherInterface
{
    public function getUser(): User;

    public function getFaculty(): Model;

    public function getUserId(): int;

    public function getFacultyId(): int;

    public function getCreatedAt(): string;

    public function getUpdatedAt(): string;
}
