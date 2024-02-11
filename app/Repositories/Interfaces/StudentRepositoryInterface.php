<?php

namespace App\Repositories\Interfaces;

interface StudentRepositoryInterface
{
    public function getOne(array $filters = []);

    public function getAll(array $filters = []);
}
