<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Repositories\Faculty;

class FacultyController extends Controller
{
    /** @var Faculty */
    private $facultyRepository;

    /**
     * @param Faculty $facultyRepository
     * @return void
     */
    public function __construct(Faculty $facultyRepository)
    {
        $this->facultyRepository = $facultyRepository;
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
}
