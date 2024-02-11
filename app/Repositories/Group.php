<?php

namespace App\Repositories;

use App\Models\Group as GroupModel;
use App\Repositories\Interfaces\GroupRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Group extends RepositoryAbstract implements GroupRepositoryInterface
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return GroupModel::class;
    }

    /**
     * @param array $filters
     * @return Builder|Model|GroupModel|object|null
     */
    public function getOne(array $filters = [])
    {
        $query = GroupModel::query();

        if (!empty($filters['id'])) {
            $query = $query->where('id', (int) $filters['id']);
        }

        if (!empty($filters['title'])) {
            $query = $query->where('title','like','%' . $filters['title'] . '%');
        }

        return $query->first();
    }

    /**
     * @param array $filters
     * @return Builder[]|Collection|GroupModel[]
     */
    public function getAll(array $filters = [])
    {
        $query = GroupModel::query();

        if (!empty($filters['id'])) {
            $query = $query->where('id', (int) $filters['id']);
        }

        if (!empty($filters['course_id'])) {
            $query = $query->where('course_id', (int) $filters['course_id']);
        }

        if (!empty($filters['title'])) {
            $query = $query->where('title','like','%' . $filters['title'] . '%');
        }

        return $query->get();
    }
}
