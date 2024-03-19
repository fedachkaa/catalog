<?php

namespace App\Models\Interfaces;

interface TeacherSubjectInterface
{
    public function getTeacherId(): int;

    public function getSubjectId(): int;
}
