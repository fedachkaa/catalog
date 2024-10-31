<?php

namespace App\Models\Interfaces;

use App\Models\Catalog;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Model;

interface TopicRequestInterface
{
    public function getTopic(): Model|Topic;

    public function getStudent(): Model;

    public function getCatalog(): Model|Catalog;

    public function getId(): int;

    public function getCatalogId(): int;

    public function getTopicId(): int;

    public function getStudentId(): int;

    public function getStatus(): string;

    public function getCreatedAt(): string;

    public function getUpdatedAt(): string;
}
