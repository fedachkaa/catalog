<?php

namespace App\Repositories;

use App\Models\Student as StudentModel;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use App\Models\Teacher as TeacherModel;
use App\Exporters\Teacher as TeacherExporter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Teacher extends RepositoryAbstract implements TeacherRepositoryInterface
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return TeacherModel::class;
    }

    /**
     * @return string
     */
    public function getExporterName(): string
    {
        return TeacherExporter::class;
    }

    /**
     * @param array $filters
     * @return Builder|Model|StudentModel|object|null
     */
    public function getOne(array $filters = [])
    {
        $query = TeacherModel::query();

        if (!empty($filters['user_id'])) {
            $query = $query->where('user_id', (int) $filters['user_id']);
        }

        return $query->first();
    }

    /**
     * @param array $filters
     * @return Builder[]|Collection|TeacherModel[]
     */
    public function getAll(array $filters = [])
    {
        $query = TeacherModel::query();

        if (!empty($filters['user_id'])) {
            $query = $query->where('user_id', (int) $filters['user_id']);
        }

        if (!empty($filters['faculty_id'])) {
            $query = $query->where('faculty_id', (int) $filters['faculty_id']);
        }

        if (!empty($filters['university_id'])) {
            $query = $query->join('faculties', 'faculties.id', '=', 'teachers.faculty_id');

            $query = $query->where('faculties.university_id', (int) $filters['university_id']);
        }

        if (!empty($filters['searchText'])) {
            $query = $query->join('users', 'users.id', '=', 'teachers.user_id');
            $query->where('users.first_name', 'LIKE', '%' . $filters['searchText'] . '%')
                ->orWhere('users.last_name', 'LIKE', '%' . $filters['searchText'] . '%');
        }

        return $query->get();
    }
}
