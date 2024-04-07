<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface CatalogGroupInterface
{
    public function getCatalog(): Model;

    public function getGroup(): Model;

    public function getCatalogId(): int;

    public function getGroupId(): int;
}
