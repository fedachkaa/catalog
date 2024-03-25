<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface CatalogInterface
{
    public function getGroup(): Model;

    public function getTopics(): Collection;

    public function getSupervisors(): Collection;

    public function getId(): int;

    public function getGroupId(): int;

    public function getType(): string;
}
