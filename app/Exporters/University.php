<?php

namespace App\Exporters;

use App\Repositories\Faculty as FacultyRepository;
use App\Repositories\Interfaces\CatalogRepositoryInterface;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
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
            'teachers' => 'teachers',
            'students' => 'students',
            'catalogs' => 'catalogs',
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

        return $facultyRepository->exportAll($university->getFaculties(), ['courses']);
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

    /**
     * @param UniversityModel $university
     * @return array
     */
    protected function expandTeachers(UniversityModel $university): array
    {
        $teacherRepository = App::get(TeacherRepositoryInterface::class);

        $teachers = [];
        /** @var \App\Models\Faculty $faculty */
        foreach ($university->getFaculties() as $faculty) {
            $teachers = array_merge($teachers, $teacherRepository->exportAll($faculty->getTeachers(), ['user', 'faculty', 'subjects']));
        }

        return $teachers;
    }

    /**
     * @param UniversityModel $university
     * @return array
     */
    protected function expandStudents(UniversityModel $university): array
    {
        $studentRepository = App::get(StudentRepositoryInterface::class);

        $students = [];

        /** @var \App\Models\Faculty $faculty */
        foreach ($university->getFaculties() as $faculty) {
            /** @var \App\Models\Course $course */
            foreach ($faculty->getCourses() as $course) {
                foreach ($course->getGroups() as $group) {
                    $students = array_merge($students, $studentRepository->exportAll($group->getStudents(), ['user', 'faculty', 'course', 'group']));
                }
            }
        }

        return $students;
    }

    /**
     * @param UniversityModel $university
     * @return array
     */
    protected function expandCatalogs(UniversityModel $university): array
    {
        $catalogRepository = App::get(CatalogRepositoryInterface::class);

        return $catalogRepository->exportAll($university->getCatalogs(), ['faculty', 'course', 'groups', 'supervisors']);
    }
}
