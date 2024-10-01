<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface CatalogTopicInterface
{
    public function getCatalog(): Model;

    public function getTeacher(): Model;

    public function getStudent(): ?Model;

    public function getStudentRequests(): Collection;

    public function getId(): int;

    public function getCatalogId(): int;

    public function getTeacherId(): int;

    public function getStudentId(): int;

    public function getTopic(): string;

    public function getTopicId(): int;
}
