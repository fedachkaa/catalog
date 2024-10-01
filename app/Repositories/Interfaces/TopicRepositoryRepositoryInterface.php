<?php

namespace App\Repositories\Interfaces;

interface TopicRepositoryRepositoryInterface
{
    public function getOne(array $filters = []);

    public function getAll(array $filters = []);
}
