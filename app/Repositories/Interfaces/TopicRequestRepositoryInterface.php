<?php

namespace App\Repositories\Interfaces;

interface TopicRequestRepositoryInterface
{
    public function getOne(array $filters = []);

    public function getAll(array $filters = []);
}
