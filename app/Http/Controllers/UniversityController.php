<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUniversityRequest;
use App\Models\University;
use App\Models\UserRole;
use App\Repositories\Interfaces\UniversityRepositoryInterface;
use App\Services\UniversityService;
use App\Services\UserService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UniversityController extends Controller
{
    /** @var UniversityService */
    private $universityService;

    /** @var UserService */
    private $userService;

    /** @var UniversityRepositoryInterface */
    private $universityRepository;

    /**
     * @param UniversityService $universityService
     * @param UserService $userService
     * @param UniversityRepositoryInterface $universityRepository
     */
    public function __construct(
        UniversityService $universityService,
        UserService $userService,
        UniversityRepositoryInterface $universityRepository,
    ){
        $this->universityService = $universityService;
        $this->userService = $userService;
        $this->universityRepository = $universityRepository;
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
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(CreateUniversityRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $user = $this->userService->createUser(array_merge($data['user'], ['role_id' => UserRole::USER_ROLE_UNIVERSITY_ADMIN]));
            $university = $this->universityService->createUniversity(array_merge(['admin_id' => $user->getId()], $data));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // TODO add view
            return response()->json([
                'message' => 'Internal serve error',
                'error' => $e->getMessage()
            ])->setStatusCode(500);
        }

        return redirect()->route('university.success');
    }

    /**
     * @param University $university
     * @return Application|Factory|View
     */
    public function getUniversity(University $university): View|Factory|Application
    {
        $university = $this->universityRepository->export($university);

        return view('userProfile.universityAdminProfile.partials.university.university-info-block', compact('university'));
    }

    /**
     * @return Application|Factory|View
     */
    public function registrationSuccess(): View|Factory|Application
    {
        return view('registrationSuccess');
    }
}
