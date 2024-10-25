<?php

namespace App\Repositories\Interfaces;

interface TopicRepositoryInterface
{
    public function getOne(array $filters = []);

    public function getAll(array $filters = []);
}
