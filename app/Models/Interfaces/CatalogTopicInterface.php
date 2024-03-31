<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface CatalogTopicInterface
{
    public function getCatalog(): Model;

    public function getTeacher(): Model;

    public function getStudent(): ?Model;

    public function getId(): int;

    public function getCatalogId(): int;

    public function getTopic(): string;
}
