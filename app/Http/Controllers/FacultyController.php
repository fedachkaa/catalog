<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostPutFacultyRequest;
use App\Models\Faculty;
use App\Models\University;
use App\Repositories\Interfaces\FacultyRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    /** @var int */
    const PAGINATION_LIMIT = 1;

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
     * @param University $university
     * @return Application|Factory|View
     */
    public function getFaculties(University $university): View|Factory|Application
    {
        return view('userProfile.universityAdminProfile.partials.faculties.faculties-block');
    }

    /**
     * AJAX Route
     *
     * @param Request $request
     * @param University $university
     * @return JsonResponse
     */
    public function getFacultiesList(Request $request, University $university): JsonResponse
    {
        $searchParams = $this->getSearchParams($request);
        $totalFaculties = count($this->facultyRepository->getAll(['university_id' => $university->getId()]));
        $faculties = $this->facultyRepository->getAll(array_merge($searchParams, ['university_id' => $university->getId()]));

        return response()->json([
            'message' => 'Success',
            'data' => [
                'faculties' => $this->facultyRepository->exportAll($faculties, ['courses']),
                'pagination' => $this->getPagination($searchParams, $totalFaculties)
            ],
        ])->setStatusCode(200);
    }

    /**
     * AJAX Route
     *
     * @param PostPutFacultyRequest $request
     * @param University $university
     * @return JsonResponse
     */
    public function saveFaculty(PostPutFacultyRequest $request, University $university): JsonResponse
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
     * @param PostPutFacultyRequest $request
     * @param University $university
     * @param Faculty $faculty
     * @return JsonResponse
     */
    public function updateFaculty(PostPutFacultyRequest $request, University $university, Faculty $faculty): JsonResponse
    {
        try {
            $faculty->updateOrFail([
                'title' => $request->input('title'),
            ]);
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
     * @param Request $request
     * @return array
     */
    private function getSearchParams(Request $request): array
    {
        $searchParams = [];

        if ($request->has('page')) {
            $searchParams['page'] = (int) $request->get('page');
            $searchParams['limit'] = self::PAGINATION_LIMIT;
            $searchParams['offset'] = $request->get('page') * self::PAGINATION_LIMIT - 1;
        } else {
            $searchParams['page'] = 1;
            $searchParams['limit'] = self::PAGINATION_LIMIT;
            $searchParams['offset'] = 0;
        }

        return $searchParams;
    }

    /**
     * @param array $filters
     * @param int $totalRows
     * @return array
     */
    private function getPagination(array $filters, int $totalRows): array
    {
        $totalPages = (int)ceil($totalRows / $filters['limit']);

        return [
            'before' => $filters['page'] === 1 ? $filters['page'] : $filters['page'] - 1,
            'next' => $filters['page'] === $totalPages ? $totalPages : $filters['page'] + 1,
            'last' => $totalPages,
            'current' => $filters['page'],
            'totalPages' => $totalPages,
            'totalCount' => $totalRows,
        ];
    }
}
