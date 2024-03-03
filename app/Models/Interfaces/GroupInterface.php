<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface GroupInterface
{
    public function getCourse(): Model;

    public function getStudents(): Collection;

    public function getId(): int;

    public function getCourseId(): int;

    public function getTitle(): string;
}
