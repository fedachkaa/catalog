<?php

namespace App\Repositories;

use App\Models\Faculty as FacultyModel;
use App\Repositories\Interfaces\FacultyRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Faculty extends RepositoryAbstract implements FacultyRepositoryInterface
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return FacultyModel::class;
    }

    /**
     * @param array $filters
     * @return Builder|Model|FacultyModel|object|null
     */
    public function getOne(array $filters = [])
    {
        $query = FacultyModel::query();

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
     * @return Builder[]|Collection|FacultyModel[]
     */
    public function getAll(array $filters = [])
    {
        $query = FacultyModel::query();

        return $query->get();
    }
}
