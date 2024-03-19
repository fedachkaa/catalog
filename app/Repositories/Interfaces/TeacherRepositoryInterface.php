<?php

namespace App\Repositories\Interfaces;

interface TeacherRepositoryInterface
{
    public function getOne(array $filters = []);

    public function getAll(array $filters = []);
}
