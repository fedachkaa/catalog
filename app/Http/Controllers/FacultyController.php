<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\University;
use App\Repositories\Interfaces\FacultyRepositoryInterface;
use App\Repositories\Interfaces\GroupRepositoryInterface;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    /** @var FacultyRepositoryInterface */
    private $facultyRepository;

    /**
     * @param FacultyRepositoryInterface $facultyRepository
     */
    public function __construct(FacultyRepositoryInterface $facultyRepository)
    {
        $this->facultyRepository = $facultyRepository;
    }

    /**
     * AJAX Route
     *
     * @param Request $request
     * @param University $university
     * @return JsonResponse
     */
    public function saveFaculty(Request $request, University $university): JsonResponse
    {
        try {
            /** @var Faculty $faculty */
            $faculty = $this->facultyRepository->getNew([
                'university_id' => $university->getId(),
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
            'data' => $this->facultyRepository->export($faculty, ['courses']),
        ])->setStatusCode(200);
    }

    /**
     * AJAX Route
     *
     * @param \App\Models\University $university
     * @return JsonResponse
     */
    public function getFaculties(University $university): JsonResponse
    {
        $faculties = $this->facultyRepository->getAll(['university_id' => $university->getId()]);

        return response()->json([
            'message' => 'Success',
            'data' => [
                'faculties' =>$this->facultyRepository->exportAll($faculties, ['courses']),
                ],
        ])->setStatusCode(200);
    }

    /**
     * AJAX Route
     *
     * @param University $university
     * @param Faculty $faculty
     * @return JsonResponse
     */
    public function getFaculty(University $university, Faculty $faculty): JsonResponse
    {
        $faculty = $this->facultyRepository->getOne([
            'id' => $faculty->getId(),
            'university_id' => $university->getId(),
        ]);

        return response()->json([
            'message' => 'Success',
            'data' => $this->facultyRepository->export($faculty, ['courses']),
        ])->setStatusCode(200);
    }
}