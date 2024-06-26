<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Http\Requests\PostPutTeacherRequest;
use App\Models\Interfaces\UniversityInterface;
use App\Models\Teacher;
use App\Models\University;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use App\Services\TeacherService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    /** @var int */
    const PAGINATION_LIMIT = 10;

    /** @var TeacherService */
    private $teacherService;

    /** @var TeacherRepositoryInterface */
    private $teacherRepository;

    /**
     * @param TeacherService $teacherService
     * @param TeacherRepositoryInterface $teacherRepository
     */
    public function __construct(TeacherService $teacherService, TeacherRepositoryInterface $teacherRepository)
    {
        $this->teacherService = $teacherService;
        $this->teacherRepository = $teacherRepository;
    }

    /**
     * @param UniversityInterface $university
     * @return Application|Factory|View
     */
    public function getTeachers(UniversityInterface $university): View|Factory|Application
    {
        return view('userProfile.universityAdminProfile.partials.teachers.teachers-block');
    }

    /**
     * AJAX Route
     *
     * @param Request $request
     * @param UniversityInterface $university
     * @return JsonResponse
     */
    public function getTeachersList(Request $request, UniversityInterface $university): JsonResponse
    {
        $searchParams = $this->getSearchParams($request);
        $totalTeachers = count($this->teacherRepository->getAll(['university_id' => $university->getId()]));
        $teachers = $this->teacherRepository->getAll(array_merge($searchParams, ['university_id' => $university->getId()]));

        return response()->json([
            'message' => 'Success',
            'data' => [
                'teachers' => $this->teacherRepository->exportAll($teachers, ['user', 'faculty', 'subjects']),
                'pagination' => $this->getPagination($searchParams, $totalTeachers),
            ],
        ])->setStatusCode(200);
    }

    /**
     * @param PostPutTeacherRequest $request
     * @param UniversityInterface $university
     * @return JsonResponse
     */
    public function saveTeacher(PostPutTeacherRequest $request, UniversityInterface $university): JsonResponse
    {
        try {
            /** @var Teacher $teacher */
            $teacher = $this->teacherService->saveTeacher([
                'first_name' => $request->post('first_name'),
                'last_name' => $request->post('last_name'),
                'email' => $request->post('email'),
                'phone_number' => $request->post('phone_number'),
                'faculty_id' => $request->post('faculty_id'),
                'subjectsIds' => $request->post('subjectsIds'),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Internal serve error',
                'error' => $e->getMessage()
            ])->setStatusCode(500);
        }

        event(new UserRegistered($teacher->getUser()));

        return response()->json([
            'message' => 'Success',
            'data' => $this->teacherRepository->export($teacher, ['user', 'faculty', 'subjects']),
        ])->setStatusCode(200);
    }

    /**
     * @param University $university
     * @param Teacher $teacher
     * @return JsonResponse
     */
    public function editTeacher(University $university, Teacher $teacher): JsonResponse
    {
        return response()->json([
            'message' => 'Success',
            'data' => $this->teacherRepository->export($teacher, ['user', 'faculty', 'subjects']),
        ])->setStatusCode(200);
    }

    /**
     * @param PostPutTeacherRequest $request
     * @param University $university
     * @param Teacher $teacher
     * @return JsonResponse
     */
    public function updateTeacher(PostPutTeacherRequest $request, University $university, Teacher $teacher): JsonResponse
    {
        try {
            $this->teacherService->updateTeacher($teacher, $request->validated());
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Internal serve error',
                'error' => $e->getMessage()
            ])->setStatusCode(500);
        }

        return response()->json([
            'message' => 'Success',
            'data' => $this->teacherRepository->export($teacher, ['user', 'faculty', 'subjects']),
        ])->setStatusCode(200);
    }

    /**
     * @param University $university
     * @param Teacher $teacher
     * @return JsonResponse
     */
    public function deleteTeacher(University $university, Teacher $teacher): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->teacherService->deleteTeacher($teacher);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Internal serve error',
                'error' => $e->getMessage()
            ])->setStatusCode(500);
        }
        DB::commit();

        return response()->json([
            'message' => 'Success',
        ])->setStatusCode(200);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getSearchParams(Request $request): array
    {
        $searchParams = [];

        if ($request->has('searchText')) {
            $searchParams['searchText'] = $request->get('searchText');
        }

        if ($request->has('facultyId')) {
            $searchParams['faculty_id'] = $request->get('facultyId');
        }

        if ($request->has('page')) {
            $searchParams['page'] = (int) $request->get('page');
            $searchParams['limit'] = self::PAGINATION_LIMIT;
            $searchParams['offset'] = ($request->get('page') - 1 ) * self::PAGINATION_LIMIT;
        } else {
            $searchParams['page'] = 1;
            $searchParams['limit'] = self::PAGINATION_LIMIT;
            $searchParams['offset'] = 0;
        }
        return $searchParams;
    }
}
