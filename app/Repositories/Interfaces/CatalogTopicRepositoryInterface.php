<?php

namespace App\Repositories\Interfaces;

interface CatalogTopicRepositoryInterface
{
    public function getOne(array $filters = []);

    public function getAll(array $filters = []);
}
