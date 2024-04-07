<?php

namespace App\Services;

use App\Models\Interfaces\TeacherInterface;
use App\Models\UserRole;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use App\Repositories\Interfaces\TeacherSubjectRepositoryInterface;

class TeacherService
{
    /** @var UserService $userService */
    private $userService;

    /** @var TeacherRepositoryInterface */
    private $teacherRepository;

    /** @var TeacherSubjectRepositoryInterface */
    private $teacherSubjectRepository;

    /**
     * @param UserService $userService
     * @param TeacherRepositoryInterface $teacherRepository
     * @param TeacherSubjectRepositoryInterface $teacherSubjectRepository
     */
    public function __construct(
        UserService $userService,
        TeacherRepositoryInterface $teacherRepository,
        TeacherSubjectRepositoryInterface $teacherSubjectRepository
    ){
        $this->userService = $userService;
        $this->teacherRepository = $teacherRepository;
        $this->teacherSubjectRepository = $teacherSubjectRepository;
    }

    /**
     * @param array $data
     * @return TeacherInterface
     * @throws \Throwable
     */
    public function saveTeacher(array $data) : TeacherInterface
    {
        $user = $this->userService->createUser([
            'role_id' => UserRole::USER_ROLE_TEACHER,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
        ]);

        /** @var TeacherInterface $teacher */
        $teacher = $this->teacherRepository->getNew([
            'user_id' => $user->getId(),
            'faculty_id' => $data['faculty_id'],
        ]);

        $teacher->saveOrFail();

        if ($data['subjectsIds']) {
            foreach ($data['subjectsIds'] as $subjectId) {
                $teacherSubject = $this->teacherSubjectRepository->getNew([
                    'subject_id' => (int) $subjectId,
                    'teacher_id' => $teacher->getUserId(),
                ]);

                $teacherSubject->saveOrFail();
            }
        }

        return $teacher;
    }
}
