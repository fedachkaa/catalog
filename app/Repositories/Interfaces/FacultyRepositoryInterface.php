<?php

namespace App\Repositories\Interfaces;

interface FacultyRepositoryInterface
{
    public function getOne(array $filters = []);

    public function getAll(array $filters = []);
}
