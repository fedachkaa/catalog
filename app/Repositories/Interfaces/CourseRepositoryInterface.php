<?php

namespace App\Repositories\Interfaces;

interface CourseRepositoryInterface
{
    public function getOne(array $filters = []);

    public function getAll(array $filters = []);
}
