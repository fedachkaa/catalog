<?php

namespace App\Repositories;

use App\Models\Course as CourseModel;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Course extends RepositoryAbstract implements CourseRepositoryInterface
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return CourseModel::class;
    }

    /**
     * @param array $filters
     * @return Builder|Model|CourseModel|object|null
     */
    public function getOne(array $filters = [])
    {
        $query = CourseModel::query();

        if (!empty($filters['id'])) {
            $query = $query->where('id', (int) $filters['id']);
        }

        if (!empty($filters['course'])) {
            $query = $query->where('course', (int) $filters['course']);
        }

        return $query->first();
    }

    /**
     * @param array $filters
     * @return Builder[]|Collection|CourseModel[]
     */
    public function getAll(array $filters = [])
    {
        $query = CourseModel::query();

        if (!empty($filters['id'])) {
            $query = $query->where('id', (int) $filters['id']);
        }

        if (!empty($filters['faculty_id'])) {
            $query = $query->where('faculty_id', (int) $filters['faculty_id']);
        }

        return $query->get();
    }
}
