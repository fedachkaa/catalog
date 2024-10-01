<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface TopicRequestInterface
{
    public function getCatalogTopic(): Model;

    public function getStudent(): Model;

    public function getId(): int;

    public function getTopicId(): int;

    public function getStudentId(): int;

    public function getStatus(): string;

    public function getCreatedAt(): string;

    public function getUpdatedAt(): string;
}
