<?php

namespace App\Services;

use App\Models\Interfaces\StudentInterface;
use App\Models\Student;
use App\Models\UserRole;
use App\Repositories\Interfaces\StudentRepositoryInterface;

class StudentService
{
    /** @var UserService $userService */
    private $userService;

    /** @var StudentRepositoryInterface */
    private $studentRepository;

    /**
     * @param UserService $userService
     * @param StudentRepositoryInterface $studentRepository
     */
    public function __construct(
        UserService    $userService,
        StudentRepositoryInterface $studentRepository
    )
    {
        $this->userService = $userService;
        $this->studentRepository = $studentRepository;
    }

    /**
     * @param array $data
     * @return StudentInterface
     * @throws \Throwable
     */
    public function saveStudent(array $data) : StudentInterface
    {
        $user = $this->userService->createUser([
            'role_id' => UserRole::USER_ROLE_STUDENT,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
        ]);

        /** @var StudentInterface $student */
        $student = $this->studentRepository->getNew([
            'user_id' => $user->getId(),
            'group_id' => $data['group_id'],
        ]);

        $student->saveOrFail();

        return $student;
    }

    /**
     * @param Student $student
     * @param array $data
     * @return Student
     * @throws \Throwable
     */
    public function updateStudent(Student $student, array $data): Student
    {
        $this->userService->updateUser($student->getUser(), $data);

        if (isset($data['group_id'])) {
            $student->updateOrFail([
                'group_id' => $data['group_id'],
            ]);
        }

        return $student;
    }

    /**
     * @param Student $student
     * @return bool
     */
    public function deleteStudent(Student $student): bool
    {
        $student->getUser()->delete();

        return true;
    }
}
