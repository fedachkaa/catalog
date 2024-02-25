<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Faculty;
use App\Models\University;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController
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
     * AJAX Route
     *
     * @param Request $request
     * @param University $university
     * @param Faculty $faculty
     * @return JsonResponse
     */
    public function saveCourse(Request $request, University $university, Faculty $faculty): JsonResponse
    {
        try {
            /** @var Course $course */
            $course = $this->courseRepository->getNew([
                'faculty_id' => $faculty->getId(),
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
}
