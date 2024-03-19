<?php

namespace App\Repositories\Interfaces;

interface TeacherSubjectRepositoryInterface
{
    public function getOne(array $filters = []);

    public function getAll(array $filters = []);
}
