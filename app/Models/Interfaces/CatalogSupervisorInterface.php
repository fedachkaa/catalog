<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface CatalogSupervisorInterface
{
    public function getCatalog(): Model;

    public function getTeacher(): Model;

    public function getCatalogId(): int;

    public function getTeacherId(): int;
}
