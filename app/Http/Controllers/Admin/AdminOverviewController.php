<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\UniversityRepositoryInterface;
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
     * @return View
     */
    public function overview(): View
    {
        $universities = $this->universityRepository->exportAll($this->universityRepository->getAll());
        return view('admin.overview', compact('universities'));
    }
}
