<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface UserRoleInterface
{
    public function getUsers(): Collection;

    public function getId(): int;

    public function getRole(): string;
}
