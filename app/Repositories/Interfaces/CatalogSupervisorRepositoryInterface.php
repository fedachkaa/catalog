<?php

namespace App\Repositories\Interfaces;

interface CatalogSupervisorRepositoryInterface
{
    public function getOne(array $filters = []);

    public function getAll(array $filters = []);
}
