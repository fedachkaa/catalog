<?php

namespace App\Repositories\Interfaces;

interface UniversityRepositoryInterface
{
    public function getOne(array $filters = []);

    public function getAll(array $filters = []);
}
