<?php

namespace App\Exporters;

use Illuminate\Database\Eloquent\Model;
use App\Models\Faculty as FacultyModel;
use Illuminate\Support\Facades\App;
use App\Repositories\University as UniversityRepository;

class Faculty extends ExporterAbstract
{
    /**
     * @return string[]
     */
    public function getAllowedExpands(): array
    {
        return [
            'university' => 'university',
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
    public function expandUniversity(FacultyModel $faculty): array
    {
        /** @var UniversityRepository $facultyRepository */
        $universityRepository = App::make(UniversityRepository::class);

        return $universityRepository->export($faculty->getUniversity());
    }
}
