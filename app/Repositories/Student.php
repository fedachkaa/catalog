<?php

namespace App\Repositories;

use App\Models\Student as StudentModel;
use App\Exporters\Student as StudentExporter;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Student extends RepositoryAbstract implements StudentRepositoryInterface
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return StudentModel::class;
    }

    /**
     * @return string
     */
    public function getExporterName(): string
    {
        return StudentExporter::class;
    }

    /**
     * @param array $filters
     * @return Builder|Model|StudentModel|object|null
     */
    public function getOne(array $filters = [])
    {
        $query = StudentModel::query();

        if (!empty($filters['user_id'])) {
            $query = $query->where('user_id', (int) $filters['user_id']);
        }

        return $query->first();
    }

    /**
     * @param array $filters
     * @return Builder[]|Collection|StudentModel[]
     */
    public function getAll(array $filters = [])
    {
        $query = StudentModel::query();

        $query = $this->prepareJoins($query, $filters);

        if (!empty($filters['user_id'])) {
            $query = $query->where('user_id', (int) $filters['user_id']);
        }

        if (!empty($filters['group_id'])) {
            $query = $query->where('group_id', (int) $filters['group_id']);
        }

        if (!empty($filters['groupTitle'])) {
            $query = $query->where(\App\Models\Group::TABLE_NAME . '.title', 'LIKE', '%' . $filters['groupTitle'] . '%');
        }

        if (!empty($filters['university_id'])) {
            $query = $query->where(\App\Models\University::TABLE_NAME . '.id', $filters['university_id']);
        }

        if (!empty($filters['surname'])) {
            $query = $query->where(\App\Models\User::TABLE_NAME . '.last_name', 'LIKE', '%' . $filters['surname'] . '%');
        }

        if (!empty($filters['email'])) {
            $query = $query->where(\App\Models\User::TABLE_NAME . '.email', 'LIKE', '%' . $filters['email'] . '%');
        }

        if (!empty($filters['faculty_id'])) {
            $query = $query->where(\App\Models\Course::TABLE_NAME . '.faculty_id', '=', (int) $filters['faculty_id']);
        }

        if (!empty($filters['courseTitle'])) {
            $query = $query->where(\App\Models\Course::TABLE_NAME . '.course', 'LIKE', '%' . $filters['courseTitle'] . '%');
        }

        return $query->get();
    }

    /**
     * @param Builder $query
     * @param array $filter
     * @return Builder
     */
    private function prepareJoins(Builder $query, array $filter): Builder
    {
        if (!empty($filter['surname']) || !empty($filter['email'])) {
            $query = $query->join(\App\Models\User::TABLE_NAME, \App\Models\User::TABLE_NAME. '.id', '=', StudentModel::TABLE_NAME . '.user_id');
        }

        if (
            !empty($filter['groupTitle']) ||
            !empty($filter['group_id']) ||
            !empty($filter['university_id']) ||
            !empty($filter['faculty_id']) ||
            !empty($filter['course_id']) ||
            !empty($filter['courseTitle'])
        ) {
            $query = $query->join(\App\Models\Group::TABLE_NAME, \App\Models\Group::TABLE_NAME . '.id', '=', StudentModel::TABLE_NAME . '.group_id')
                ->join(\App\Models\Course::TABLE_NAME, \App\Models\Course::TABLE_NAME . '.id', '=', \App\Models\Group::TABLE_NAME . '.course_id')
                ->join(\App\Models\Faculty::TABLE_NAME, \App\Models\Faculty::TABLE_NAME . '.id', '=', \App\Models\Course::TABLE_NAME . '.faculty_id')
                ->join(\App\Models\University::TABLE_NAME, \App\Models\University::TABLE_NAME . '.id', '=', \App\Models\Faculty::TABLE_NAME . '.university_id');
        }

        return $query;
    }
}
