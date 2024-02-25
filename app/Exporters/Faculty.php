<?php

namespace App\Exporters;

use Illuminate\Database\Eloquent\Model;
use App\Models\Faculty as FacultyModel;
use Illuminate\Support\Facades\App;
use App\Repositories\University as UniversityRepository;
use App\Repositories\Course as CourseRepository;

class Faculty extends ExporterAbstract
{
    /**
     * @return string[]
     */
    public function getAllowedExpands(): array
    {
        return [
            'university' => 'university',
            'courses' => 'courses'
        ];
    }

    /**
     * @param FacultyModel|Model $model
     * @return array
     */
    public function exportModel(Model $model): array
    {
        return [
            'id' => $model->getId(),
            'university_id' => $model->getUniversityId(),
            'title' => $model->getTitle(),
        ];
    }

    /**
     * @param FacultyModel $faculty
     * @return array
     */
    protected function expandUniversity(FacultyModel $faculty): array
    {
        /** @var UniversityRepository $facultyRepository */
        $universityRepository = App::make(UniversityRepository::class);

        return $universityRepository->export($faculty->getUniversity());
    }

    /**
     * @param FacultyModel $faculty
     * @return array
     */
    protected function expandCourses(FacultyModel $faculty): array
    {
        /** @var CourseRepository $courseRepository */
        $courseRepository = App::make(CourseRepository::class);

        return $courseRepository->exportAll($faculty->getCourses());
    }
}
