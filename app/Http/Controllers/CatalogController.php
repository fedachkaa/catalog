<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostPutCatalogRequest;
use App\Http\Requests\PostPutTopicRequest;
use App\Models\Catalog;
use App\Models\CatalogTopic;
use App\Models\University;
use App\Repositories\Interfaces\CatalogGroupRepositoryInterface;
use App\Repositories\Interfaces\CatalogRepositoryInterface;
use App\Repositories\Interfaces\CatalogSupervisorRepositoryInterface;
use App\Repositories\Interfaces\CatalogTopicRepositoryInterface;
use App\Repositories\Interfaces\FacultyRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{
    /** @var CatalogRepositoryInterface */
    private $catalogRepository;

    /** @var CatalogGroupRepositoryInterface */
    private $catalogGroupRepository;

    /** @var CatalogSupervisorRepositoryInterface */
    private $catalogSupervisorRepository;

    /** @var CatalogTopicRepositoryInterface */
    private $catalogTopicRepository;

    /** @var FacultyRepositoryInterface */
    private $facultyRepository;

    /**
     * @param CatalogRepositoryInterface $catalogRepository
     * @param CatalogGroupRepositoryInterface $catalogGroupRepository
     * @param CatalogSupervisorRepositoryInterface $catalogSupervisorRepository ,
     * @param CatalogTopicRepositoryInterface $catalogTopicRepository
     * @param FacultyRepositoryInterface $facultyRepository
     */
    public function __construct(
        CatalogRepositoryInterface $catalogRepository,
        CatalogGroupRepositoryInterface $catalogGroupRepository,
        CatalogSupervisorRepositoryInterface $catalogSupervisorRepository,
        CatalogTopicRepositoryInterface $catalogTopicRepository,
        FacultyRepositoryInterface $facultyRepository
    ){
        $this->catalogRepository = $catalogRepository;
        $this->catalogGroupRepository = $catalogGroupRepository;
        $this->catalogSupervisorRepository = $catalogSupervisorRepository;
        $this->catalogTopicRepository = $catalogTopicRepository;
        $this->facultyRepository = $facultyRepository;
    }

    /**
     * @param University $university
     * @return Application|Factory|View
     */
    public function getCatalogs(University $university): View|Factory|Application
    {
        return view('universityAdminProfile.partials.catalogs.catalogs-block');
    }

    /**
     * @param Request $request
     * @param University $university
     * @return JsonResponse
     */
    public function getCatalogsList(Request $request, University $university): JsonResponse
    {
        $catalogs = $this->catalogRepository->getAll(['university_id' => $university->getId()]);

        return response()->json([
            'message' => 'Success',
            'data' => $this->catalogRepository->exportAll($catalogs, ['groups', 'supervisors', 'faculty', 'course']),
        ])->setStatusCode(200);
    }

    /**
     * @param PostPutCatalogRequest $request
     * @param University $university
     * @return JsonResponse
     */
    public function saveCatalog(PostPutCatalogRequest $request, University $university): JsonResponse
    {
        DB::beginTransaction();

        try {
            /** @var Catalog $catalog */
            $catalog = $this->catalogRepository->getNew([
                'type' => $request->post('type'),
            ]);

            $catalog->saveOrFail();

            foreach ($request->post('groupsIds') as $groupId) {
                $catalogGroup = $this->catalogGroupRepository->getNew([
                    'catalog_id' => $catalog->getId(),
                    'group_id' => (int) $groupId,
                ]);

                $catalogGroup->saveOrFail();
            }

            foreach ($request->post('teachersIds') as $teacherId) {
                $catalogSupervisor = $this->catalogSupervisorRepository->getNew([
                    'catalog_id' => $catalog->getId(),
                    'teacher_id' => (int) $teacherId,
                ]);

                $catalogSupervisor->saveOrFail();
            }
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
            'data' => $this->catalogRepository->export($catalog, ['groups', 'supervisors', 'faculty', 'course']),
        ])->setStatusCode(200);
    }

    /**
     * @param University $university
     * @param Catalog $catalog
     * @return Application|Factory|View
     */
    public function editCatalog(University $university, Catalog $catalog): View|Factory|Application
    {
        $catalogData = $this->catalogRepository->export($catalog, ['topics', 'groups', 'supervisors', 'faculty', 'course']);
        $faculties = $this->facultyRepository->exportAll($this->facultyRepository->getAll(['university_id' => $university->getId()]));

        return view('universityAdminProfile.partials.catalogs.edit-catalog', compact('catalogData', 'faculties'));
    }

    /**
     * @param PostPutTopicRequest $request
     * @param University $university
     * @param Catalog $catalog
     * @return JsonResponse
     */
    public function saveCatalogTopic(PostPutTopicRequest $request, University $university, Catalog $catalog): JsonResponse
    {
        try {
            $catalogTopic = $this->catalogTopicRepository->getNew([
                'catalog_id' => $catalog->getId(),
                'teacher_id' => $request->input('teacher_id'),
                'topic' => $request->input('topic'),
            ]);

            $catalogTopic->saveOrFail();
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Internal serve error',
                'error' => $e->getMessage()
            ])->setStatusCode(500);
        }

        return response()->json([
            'message' => 'Success',
            'data' => $this->catalogTopicRepository->export($catalogTopic, ['teacher', 'student']),
        ])->setStatusCode(200);
    }

    /**
     * @param PostPutTopicRequest $request
     * @param University $university
     * @param Catalog $catalog
     * @param CatalogTopic $catalogTopic
     * @return JsonResponse
     */
    public function updateCatalogTopic(PostPutTopicRequest $request, University $university, Catalog $catalog, CatalogTopic $catalogTopic): JsonResponse
    {
        try {
            $catalogTopic->updateOrFail([
                'topic' => $request->input('topic'),
                'teacher_id' => $request->input('teacher_id'),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Internal serve error',
                'error' => $e->getMessage()
            ])->setStatusCode(500);
        }

        return response()->json([
            'message' => 'Success',
            'data' => $this->catalogTopicRepository->export($catalogTopic, ['teacher', 'student']),
        ])->setStatusCode(200);
    }
}
