<?php

namespace App\Repositories\Interfaces;

interface CatalogRepositoryInterface
{
    public function getOne(array $filters = []);

    public function getAll(array $filters = []);
}
