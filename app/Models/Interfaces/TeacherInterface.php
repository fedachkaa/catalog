<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface TeacherInterface
{
    public function getUser(): Model;

    public function getFaculty(): Model;

    public function getUserId(): int;

    public function getFacultyId(): int;

    public function getCreatedAt(): string;

    public function getUpdatedAt(): string;
}
