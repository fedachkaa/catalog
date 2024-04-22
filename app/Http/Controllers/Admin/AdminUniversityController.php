<?php

namespace App\Http\Controllers\Admin;

use App\Models\University;
use App\Repositories\Interfaces\UniversityRepositoryInterface;
use App\Services\UniversityService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminUniversityController
{
    /** @var UniversityRepositoryInterface */
    private $universityRepository;

    /** @var UniversityService */
    private $universityService;

    /**
     * @param UniversityRepositoryInterface $universityRepository
     * @param UniversityService $universityService
     */
    public function __construct(UniversityRepositoryInterface $universityRepository, UniversityService $universityService)
    {
        $this->universityRepository = $universityRepository;
        $this->universityService = $universityService;
    }

    /**
     * @param University $university
     * @return Application|Factory|View
     */
    public function universitySingle(University $university): View|Factory|Application
    {
        $universityData = $this->universityRepository->export($university, ['universityAdmin']);

        return view('admin.university.university-single', compact('universityData'));
    }

    /**
     * @param Request $request
     * @param University $university
     * @return JsonResponse
     */
    public function updateUniversity(Request $request, University $university): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->universityService->updateUniversity($university, [
                'is_active' => (bool) $request->post('is_active'),
            ]);
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
            'data' => $this->universityRepository->export($university),
        ])->setStatusCode(200);
    }
}
