<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface CourseInterface
{
    public function getFaculty(): Model;

    public function getGroups(): Collection;

    public function getStudents(): Collection;

    public function getId(): int;

    public function getFacultyId(): int;

    public function getCourse(): int;
}
