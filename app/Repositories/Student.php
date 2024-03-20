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

        if (!empty($filters['id'])) {
            $query = $query->where('id', (int) $filters['id']);
        }

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

        if (!empty($filters['id'])) {
            $query = $query->where('id', (int) $filters['id']);
        }

        if (!empty($filters['user_id'])) {
            $query = $query->where('user_id', (int) $filters['user_id']);
        }

        if (!empty($filters['group_id'])) {
            $query = $query->where('group_id', (int) $filters['group_id']);
        }

        if (!empty($filters['groupTitle'])) {
            $query = $query->where('groups.title', 'LIKE', '%' . $filters['groupTitle'] . '%');
        }

        if (!empty($filters['university_id'])) {
            $query = $query->where('universities.id', $filters['university_id']);
        }

        if (!empty($filters['surname'])) {
            $query = $query->where('users.last_name', 'LIKE', '%' . $filters['surname'] . '%');
        }

        if (!empty($filters['email'])) {
            $query = $query->where('users.email', 'LIKE', '%' . $filters['email'] . '%');
        }

        if (!empty($filters['faculty_id'])) {
            $query = $query->where('courses.faculty_id', '=', (int) $filters['faculty_id']);
        }

        if (!empty($filters['courseTitle'])) {
            $query = $query->where('courses.course', 'LIKE', '%' . $filters['courseTitle'] . '%');
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
            $query = $query->join('users', 'users.id', '=', 'students.user_id');
        }

        if (
            !empty($filter['groupTitle']) ||
            !empty($filter['university_id']) ||
            !empty($filter['faculty_id']) ||
            !empty($filter['course_id']) ||
            !empty($filter['courseTitle'])
        ) {
            $query = $query->join('groups', 'groups.id', '=', 'students.group_id')
                ->join('courses', 'courses.id', '=', 'groups.course_id')
                ->join('faculties', 'faculties.id', '=', 'courses.faculty_id')
                ->join('universities', 'universities.id', '=', 'faculties.university_id');
        }

        return $query;
    }
}
