<?php

namespace App\Repositories;

use App\Models\TeacherSubject as TeacherSubjectModel;
use App\Repositories\Interfaces\TopicRequestRepositoryInterface;
use App\Models\TopicRequest as TopicRequestModel;
use App\Exporters\TopicRequest as TopicRequestExporter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class TopicRequest extends RepositoryAbstract implements TopicRequestRepositoryInterface
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return TopicRequestModel::class;
    }

    /**
     * @return string
     */
    public function getExporterName(): string
    {
        return TopicRequestExporter::class;
    }

    /**
     * @param array $filters
     * @return Builder|Model|TopicRequestModel|object|null
     */
    public function getOne(array $filters = [])
    {
        $query = TopicRequestModel::query();

        if (!empty($filters['id'])) {
            $query = $query->where('id', (int) $filters['id']);
        }

        if (!empty($filters['topic_id'])) {
            $query = $query->where('topic_id', (int) $filters['topic_id']);
        }

        if (!empty($filters['student_id'])) {
            $query = $query->where('student_id', (int) $filters['student_id']);
        }

        return $query->first();
    }

    /**
     * @param array $filters
     * @return Builder[]|Collection|TeacherSubjectModel[]
     */
    public function getAll(array $filters = [])
    {
        $query = TopicRequestModel::query();

        if (!empty($filters['topic_id'])) {
            $query = $query->where('topic_id', (int) $filters['topic_id']);
        }

        if (!empty($filters['student_id'])) {
            $query = $query->where('student_id', (int) $filters['student_id']);
        }

        if (!empty($filters['idNotIn']) && is_array($filters['idNotIn'])) {
            $query = $query->whereNotIn('id', $filters['idNotIn']);
        }

        return $query->get();
    }
}
