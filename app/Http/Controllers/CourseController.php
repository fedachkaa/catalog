<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostPutCourseRequest;
use App\Models\Course;
use App\Models\University;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /** @var CourseRepositoryInterface */
    private $courseRepository;

    /**
     * @param CourseRepositoryInterface $courseRepository
     */
    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * @param Request $request
     * @param University $university
     * @return JsonResponse
     */
    public function getCoursesList(Request $request, University $university): JsonResponse
    {
        $searchParams = array_merge($this->getSearchParams($request), ['university_id' => $university->getId()]);
        $courses = $this->courseRepository->getAll($searchParams);

        return response()->json([
            'message' => 'Success',
            'data' => $this->courseRepository->exportAll($courses),
        ])->setStatusCode(200);
    }

    /**
     * AJAX Route
     *
     * @param PostPutCourseRequest $request
     * @param University $university
     * @return JsonResponse
     */
    public function saveCourse(PostPutCourseRequest $request, University $university): JsonResponse
    {
        try {
            /** @var Course $course */
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
     * @param Request $request
     * @return array
     */
    private function getSearchParams(Request $request): array
    {
        $searchParams = [];

        if ($request->query('facultyId')) {
            $searchParams['faculty_id'] = $request->query('facultyId');
        }

        return $searchParams;
    }
}
