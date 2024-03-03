<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface StudentInterface
{
    public function getUser(): Model;

    public function getGroup(): Model;

    public function getUserId(): int;

    public function getGroupId(): int;

    public function getCreatedAt(): string;

    public function getUpdatedAt(): string;
}
