<?php

namespace App\Repositories;

use App\Models\TeacherSubject as TeacherSubjectModel;
use App\Exporters\TeacherSubject as TeacherSubjectExporter;
use App\Repositories\Interfaces\TeacherSubjectRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class TeacherSubject extends RepositoryAbstract implements TeacherSubjectRepositoryInterface
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return TeacherSubjectModel::class;
    }

    /**
     * @return string
     */
    public function getExporterName(): string
    {
        return TeacherSubjectExporter::class;
    }

    /**
     * @param array $filters
     * @return Builder|Model|TeacherSubjectModel|object|null
     */
    public function getOne(array $filters = [])
    {
        $query = TeacherSubjectModel::query();

        if (!empty($filters['teacher_id'])) {
            $query = $query->where('teacher_id', (int) $filters['teacher_id']);
        }

        if (!empty($filters['subject_id'])) {
            $query = $query->where('subject_id', (int) $filters['subject_id']);
        }

        return $query->first();
    }

    /**
     * @param array $filters
     * @return Builder[]|Collection|TeacherSubjectModel[]
     */
    public function getAll(array $filters = [])
    {
        $query = TeacherSubjectModel::query();

        if (!empty($filters['teacher_id'])) {
            $query = $query->where('teacher_id', (int) $filters['teacher_id']);
        }

        if (!empty($filters['subject_id'])) {
            $query = $query->where('subject_id', (int) $filters['subject_id']);
        }

        return $query->get();
    }
}
