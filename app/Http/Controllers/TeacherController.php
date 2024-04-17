<?php

namespace App\Http\Controllers;

use App\Models\Interfaces\UniversityInterface;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use App\Services\TeacherService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
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
        $searchParams = array_merge($this->getSearchParams($request), ['university_id' => $university->getId()]);
        $teachers = $this->teacherRepository->getAll($searchParams);

        return response()->json([
            'message' => 'Success',
            'data' => $this->teacherRepository->exportAll($teachers, ['user', 'faculty', 'subjects']),
        ])->setStatusCode(200);
    }

    /**
     * @param Request $request
     * @param UniversityInterface $university
     * @return JsonResponse
     */
    public function saveTeacher(Request $request, UniversityInterface $university): JsonResponse
    {
        try {
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

        return response()->json([
            'message' => 'Success',
            'data' => $this->teacherRepository->export($teacher, ['user', 'faculty', 'subjects']),
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

        return $searchParams;
    }
}
