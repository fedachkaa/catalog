<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface CatalogInterface
{
    public function getGroups(): Collection;

    public function getTopics(): Collection;

    public function getSupervisors(): Collection;

    public function getId(): int;

    public function getType(): string;

    public function getIsActive(): int;

    public function getCreatedAt(): string;

    public function getUpdatedAt(): string;

    public function getActivatedAt(): string;
}
