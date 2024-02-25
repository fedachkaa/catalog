<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use App\Repositories\Student;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Repositories\Faculty;
use App\Repositories\University;
use App\Repositories\Course;
use App\Repositories\Group;
use Psy\Util\Json;

class FacultyController extends Controller
{
    /** @var Faculty */
    private $facultyRepository;

    /** @var University */
    private $universityRepository;

    /** @var Course */
    private $courseRepository;

    /** @var Group */
    private $groupRepository;

    /** @var Student */
    private $studentRepository;

    /** @var UserService */
    private $userService;

    /**
     * @param Faculty $facultyRepository
     * @return void
     */
    public function __construct(
        Faculty $facultyRepository,
        University $universityRepository,
        Course $courseRepository,
        Group $groupRepository,
        Student $studentRepository,
        UserService $userService
    ) {
        $this->facultyRepository    = $facultyRepository;
        $this->universityRepository = $universityRepository;
        $this->courseRepository     = $courseRepository;
        $this->groupRepository      = $groupRepository;
        $this->studentRepository = $studentRepository;
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function saveFaculty(Request $request): JsonResponse
    {
        try {
            $faculty = $this->facultyRepository->getNew([
                'university_id' => $request->post('university_id'),
                'title' => $request->post('title'),
            ]);
            $faculty->saveOrFail();
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Internal serve error',
                'error' => $e->getMessage()
            ])->setStatusCode(500);
        }

        return response()->json([
            'message' => 'Success',
            'data' => $faculty->toArray(),
        ])->setStatusCode(200);
    }

    /**
     * AJAX Route
     *
     * @return JsonResponse
     */
    public function getFaculties(): JsonResponse
    {
        $universityAdmin = auth()->user();

        $university = $this->universityRepository->getOne(['admin_id' => $universityAdmin->getId()]);
        $faculties  = $this->facultyRepository->getAll(['university_id' => $university->getId()]);

        return response()->json([
            'message' => 'Success',
            'data' => [
                'university_id' => $university->getId(),
                'faculties' => $this->facultyRepository->exportAll($faculties),
            ]
        ])->setStatusCode(200);
    }

    /**
     * AJAX Route
     *
     * @param int $facultyId
     * @return JsonResponse
     */
    public function getFaculty(int $facultyId): JsonResponse
    {
        $faculty = $this->facultyRepository->getOne(['id' => $facultyId]);

        return response()->json([
            'message' => 'Success',
            'data' => $this->facultyRepository->export($faculty, ['courses']),

        ])->setStatusCode(200);
    }

    /**
     * AJAX Route
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function saveFacultyCourse(Request $request): JsonResponse
    {
        try {
            $course = $this->courseRepository->getNew([
                'faculty_id' => $request->post('faculty_id'),
                'course' => $request->post('course'),
            ]);
            $course->saveOrFail();
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Internal serve error',
                'error' => $e->getMessage()
            ])->setStatusCode(500);
        }

        return response()->json([
            'message' => 'Success',
            'data' => $this->courseRepository->export($course),
        ])->setStatusCode(200);
    }

    /**
     * AJAX Route
     *
     * @param int $courseId
     * @return JsonResponse
     */
    public function getCourseGroups(int $courseId): JsonResponse
    {
        $course = $this->courseRepository->getOne(['id' => $courseId]);

        $courseData = $this->courseRepository->export($course, ['groups']);

        return response()->json([
            'message' => 'Success',
            'data' => $courseData['groups'],
        ])->setStatusCode(200);
    }

    /**
     * AJAX Route
     *
     * @param Request $request
     * @param int $courseId
     * @return JsonResponse
     */
    public function saveCourseGroup(Request $request, int $courseId): JsonResponse
    {
        $course = $this->courseRepository->getOne(['id' => $courseId]);

        if (empty($course)) {
            return response()->json([
                'message' => 'Unknown course',
            ])->setStatusCode(400);
        }

        try {
            $group = $this->groupRepository->getNew([
                'course_id' => $course->getId(),
                'title' => $request->post('title'),
            ]);
            $group->saveOrFail();
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Internal serve error',
                'error' => $e->getMessage()
            ])->setStatusCode(500);
        }

        return response()->json([
            'message' => 'Success',
            'data' => $this->groupRepository->export($group),
        ])->setStatusCode(200);
    }

    /**
     * @param int $groupId
     * @return JsonResponse
     */
    public function getGroupStudents(int $groupId): JsonResponse
    {
        $group = $this->groupRepository->getOne(['id' => $groupId]);

        return response()->json([
            'message' => 'Success',
            'data' => $this->studentRepository->exportAll($group->getStudents(), ['user']),
        ])->setStatusCode(200);
    }

    /**
     * @param Request $request
     * @param int $groupId
     * @return JsonResponse
     * @throws \Exception
     */
    public function saveStudent(Request $request, int $groupId): JsonResponse
    {
        $user = $this->userService->createUser([
            'role_id' => UserRole::USER_ROLE_STUDENT,
            'first_name' => $request->post('first_name'),
            'last_name' => $request->post('last_name'),
            'user_email' => $request->post('email'),
            'user_phone_number' => $request->post('phone_number')
        ]);

        try {
            $student = $this->studentRepository->getNew([
                'user_id' => $user->getId(),
                'group_id' => $groupId,
            ]);
            $student->saveOrFail();
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Internal serve error',
                'error' => $e->getMessage()
            ])->setStatusCode(500);
        }

        return response()->json([
            'message' => 'Success',
            'data' => $this->studentRepository->export($student, ['user']),
        ])->setStatusCode(200);
    }
}
