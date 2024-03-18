<?php

namespace App\Repositories\Interfaces;

interface SubjectRepositoryInterface
{
    public function getOne(array $filters = []);

    public function getAll(array $filters = []);

}
