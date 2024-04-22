<?php

namespace App\Exporters;

use App\Repositories\Faculty as FacultyRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use App\Models\University as UniversityModel;
use Illuminate\Support\Facades\App;


class University extends ExporterAbstract
{
    /**
     * @return string[]
     */
    public function getAllowedExpands() : array
    {
        return [
            'universityAdmin' => 'universityAdmin',
            'faculties' => 'faculties',
        ];
    }

    /**
     * @param UniversityModel|Model $model
     * @return array
     */
    protected function exportModel(Model $model): array
    {
        return [
            'id' => $model->getId(),
            'admin_id' => $model->getAdminId(),
            'name' => $model->getName(),
            'city' => $model->getCity(),
            'address' => $model->getAddress(),
            'phone_number' => $model->getPhoneNumber(),
            'email' => $model->getEmail(),
            'accreditation_level' => $model->getAccreditationLevel(),
            'founded_at' => $model->getFoundedAt(),
            'website' => $model->getWebsite(),
            'activated_at' => $model->getActivatedAt(),
            'created_at' => $model->getCreatedAt(),
            'updated_at' => $model->getUpdatedAt(),
        ];
    }

    /**
     * @param UniversityModel $university
     * @return array
     */
    protected function expandFaculties(UniversityModel $university): array
    {
        /** @var \App\Repositories\Faculty $facultyRepository */
        $facultyRepository = App::get(FacultyRepository::class);

        return $facultyRepository->exportAll($university->getFaculties());
    }

    /**
     * @param UniversityModel $university
     * @return array
     */
    protected function expandUniversityAdmin(UniversityModel $university): array
    {
        $userRepository = App::get(UserRepositoryInterface::class);

        return $userRepository->export($university->getAdmin());
    }
}
