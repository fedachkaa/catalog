<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface CatalogInterface
{
    public function getGroups(): HasMany;

    public function getTopics(): Collection;

    public function getSupervisors(): HasMany;

    public function getId(): int;

    public function getType(): string;

    public function getIsActive(): int;

    public function getCreatedAt(): string;

    public function getUpdatedAt(): string;

    public function getActivatedAt(): string;
}
