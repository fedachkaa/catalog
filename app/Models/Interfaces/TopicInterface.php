<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface TopicInterface
{
    public function getTeacher(): Model;

    public function getId(): int;

    public function getTeacherId(): int;

    public function getTopic(): string;

    public function getIsAiGenerated(): int;

    public function getCreatedAt() : string;

    public function getUpdatedAt() : string;
}
