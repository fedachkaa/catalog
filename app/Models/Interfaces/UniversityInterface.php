<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface UniversityInterface
{
    public function getAdmin(): Model;

    public function getFaculties(): Collection;

    public function getId(): int;

    public function getAdminId() : int;

    public function getName(): string;

    public function getCity() : string;

    public function getAddress(): string;

    public function getPhoneNumber(): string;

    public function getEmail(): string;

    public function getAccreditationLevel(): string;

    public function getFoundedAt(): string;

    public function getWebsite(): string;

    public function getActivatedAt(): string;

    public function getCreatedAt(): string;

    public function getUpdatedAt(): string;
}
