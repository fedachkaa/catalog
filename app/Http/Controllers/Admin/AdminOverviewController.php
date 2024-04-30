<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\UniversityRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminOverviewController extends Controller
{
    /** @var UniversityRepositoryInterface */
    private $universityRepository;

    /**
     * @param UniversityRepositoryInterface $universityRepository
     */
    public function __construct(UniversityRepositoryInterface $universityRepository)
    {
        $this->universityRepository = $universityRepository;
    }

    /**
     * @param Request $request
     * @return View
     */
    public function overview(): View
    {
        $universities = $this->universityRepository->exportAll($this->universityRepository->getAll());
        return view('admin.overview', compact('universities'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUniversities(Request $request): \Illuminate\Http\JsonResponse
    {
        $universities = $this->universityRepository->getAll($this->getSearchParams($request));

        return response()->json([
            'message' => 'Success',
            'data' => $this->universityRepository->exportAll($universities),
        ])->setStatusCode(200);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getSearchParams(Request $request): array
    {
        $searchParams = [];

        if ($request->query('title')) {
            $searchParams['title'] = $request->query('title');
        }

        if ($request->query('city')) {
            $searchParams['city'] = $request->query('city');
        }

        if ($request->query('createdAt')) {
            $searchParams['createdAt'] = $request->query('createdAt');
        }

        if ($request->query('accLevel')) {
            $searchParams['accreditation_level'] = $request->query('accLevel');
        }

        if ($request->query('email')) {
            $searchParams['email'] = $request->query('email');
        }

        if ($request->query('status')) {
            $searchParams['status'] = $request->query('status');
        }

        return $searchParams;
    }
}
