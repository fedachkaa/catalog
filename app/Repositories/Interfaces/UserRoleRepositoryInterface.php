<?php

namespace App\Repositories\Interfaces;

interface UserRoleRepositoryInterface
{
    public function getOne(array $filters = []);

    public function getAll(array $filters = []);
}
