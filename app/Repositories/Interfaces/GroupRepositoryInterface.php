<?php

namespace App\Repositories\Interfaces;

interface GroupRepositoryInterface
{
    public function getOne(array $filters = []);

    public function getAll(array $filters = []);
}
