<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface StudentInterface
{
    public function getUser(): Model;

    public function getGroup(): Model;

    public function getTopicRequests(): Collection;

    public function getUserId(): int;

    public function getGroupId(): int;

    public function getCreatedAt(): string;

    public function getUpdatedAt(): string;
}
