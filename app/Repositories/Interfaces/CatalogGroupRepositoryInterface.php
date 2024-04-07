<?php

namespace App\Repositories\Interfaces;

interface CatalogGroupRepositoryInterface
{
    public function getOne(array $filters = []);

    public function getAll(array $filters = []);
}
