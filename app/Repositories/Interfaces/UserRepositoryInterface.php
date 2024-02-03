<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function getOne(array $filters = []);

    public function getAll(array $filters = []);
}
