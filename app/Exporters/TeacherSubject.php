<?php

namespace App\Exporters;

use Illuminate\Database\Eloquent\Model;
use App\Models\TeacherSubject as TeacherSubjectModel;
use Illuminate\Support\Facades\App;

class TeacherSubject extends ExporterAbstract
{
    /**
     * @return array
     */
    public function getAllowedExpands(): array
    {
        return [
            'teacher' => 'teacher',
            'subject' => 'subject',
        ];
    }

    /**
     * @param TeacherSubjectModel|Model $model
     * @return array
     */
    public function exportModel(Model $model): array
    {
        return [
            'teacher_id' => $model->getTeacherId(),
            'subject_id' => $model->getSubjectId(),
        ];
    }

    /**
     * @param TeacherSubjectModel $teacherSubject
     * @return array
     */
    protected function expandTeacher(TeacherSubjectModel $teacherSubject)
    {
        /** @var \App\Repositories\Teacher $teacherRepository */
        $teacherRepository = App::get(\App\Repositories\Teacher::class);

        return $teacherRepository->export($teacherSubject->getTeacher());
    }

    /**
     * @param TeacherSubjectModel $teacherSubject
     * @return array
     */
    protected function expandSubject(TeacherSubjectModel $teacherSubject)
    {
        /** @var \App\Repositories\Subject $subjectRepository */
        $subjectRepository = App::get(\App\Repositories\Subject::class);

        return $subjectRepository->export($teacherSubject->getSubject());

    }
}
