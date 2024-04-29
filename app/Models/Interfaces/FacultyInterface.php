<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface FacultyInterface
{
    public function getCourses(): Collection;

    public function getUniversity(): Model;

    public function getTeachers(): Collection;

    public function getId(): int;

    public function getUniversityId(): int;

    public function getTitle(): string;
}
