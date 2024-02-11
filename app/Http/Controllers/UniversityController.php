<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUniversityRequest;
use App\Models\University;
use App\Services\UniversityService;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UniversityController extends Controller
{
    /** @var UniversityService */
    private $universityService;

    /** @var UserService */
    private $userService;

    /**
     * @param UniversityService $universityService
     * @param UserService $userService
     */
    public function __construct(UniversityService $universityService, UserService $userService)
    {
        $this->universityService = $universityService;
        $this->userService = $userService;
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('university.create', [
            'accreditationLevels' => University::AVAILABLE_ACCREDITATION_LEVELS,
        ]);
    }

    /**
     * @param CreateUniversityRequest $request
     * @return JsonResponse
     */
    public function store(CreateUniversityRequest $request) : JsonResponse
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $user = $this->userService->createUser($data);
            $university = $this->universityService->createUniversity(array_merge(['admin_id' => $user->getId()], $data));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Internal serve error',
                'error' => $e->getMessage()
            ])->setStatusCode(500);
        }

        return response()->json([
            'data' => $user->toArray(),
        ])->setStatusCode(200);
    }
}