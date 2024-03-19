<?php

namespace App\Models\Interfaces;

interface SubjectInterface
{
    public function getId(): int;

    public function getUniversityId(): int;

    public function getTitle(): string;
}
