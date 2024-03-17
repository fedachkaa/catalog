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

        $query = $query->join('groups', 'groups.id', '=', 'students.group_id');

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
            $query = $query->join('courses', 'courses.id', '=', 'groups.course_id')
                ->join('faculties', 'faculties.id', '=', 'courses.faculty_id')
                ->join('universities', 'universities.id', '=', 'faculties.university_id');

            $query = $query->where('universities.id', $filters['university_id']);
        }
        return $query->get();
    }
}
