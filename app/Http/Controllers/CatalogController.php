<?php

namespace App\Http\Controllers;

use App\Exceptions\ServiceUserException;
use App\Http\Requests\PostPutCatalogRequest;
use App\Http\Requests\PostPutTopicRequest;
use App\Models\Catalog;
use App\Models\CatalogTopic;
use App\Models\TopicRequest;
use App\Models\University;
use App\Repositories\Interfaces\CatalogRepositoryInterface;
use App\Repositories\Interfaces\CatalogTopicRepositoryInterface;
use App\Repositories\Interfaces\FacultyRepositoryInterface;
use App\Services\CatalogService;
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

    /** @var CatalogTopicRepositoryInterface */
    private $catalogTopicRepository;

    /** @var FacultyRepositoryInterface */
    private $facultyRepository;

    /** @var CatalogService */
    private $catalogService;

    /**
     * @param CatalogRepositoryInterface $catalogRepository
     * @param CatalogTopicRepositoryInterface $catalogTopicRepository
     * @param FacultyRepositoryInterface $facultyRepository
     * @param CatalogService $catalogService
     */
    public function __construct(
        CatalogRepositoryInterface $catalogRepository,
        CatalogTopicRepositoryInterface $catalogTopicRepository,
        FacultyRepositoryInterface $facultyRepository,
        CatalogService $catalogService
    ){
        $this->catalogRepository = $catalogRepository;
        $this->catalogTopicRepository = $catalogTopicRepository;
        $this->facultyRepository = $facultyRepository;
        $this->catalogService = $catalogService;
    }

    /**
     * @param University $university
     * @return Application|Factory|View
     */
    public function getCatalogs(University $university): View|Factory|Application
    {
        if (auth()->user()->isUniversityAdmin()) {
            return view('userProfile.universityAdminProfile.partials.catalogs.catalogs-block');
        } else if (auth()->user()->isTeacher()) {
            return view('userProfile.teacherProfile.partials.catalogs.catalog-block');
        } else if (auth()->user()->isStudent()) {
            return view('userProfile.studentProfile.partials.catalogs.catalog-block');
        } else {
            return view('404NotFound');
        }
    }

    /**
     * @param Request $request
     * @param University $university
     * @return JsonResponse
     */
    public function getCatalogsList(Request $request, University $university): JsonResponse
    {
        $searchParams = array_merge($this->getSearchParams($request),  ['university_id' => $university->getId()]);

        $catalogs = $this->catalogRepository->getAll($searchParams);

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

            $this->catalogService->saveCatalogGroups($catalog, $request->post('groupsIds'));
            $this->catalogService->saveCatalogTeachers($catalog, $request->post('teachersIds'));
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

        if (auth()->user()->isUniversityAdmin()) {
            return view('userProfile.universityAdminProfile.partials.catalogs.edit-catalog', compact('catalogData'));
        } else if (auth()->user()->isTeacher()) {
            return view('userProfile.teacherProfile.partials.catalogs.view-catalog', compact('catalogData'));
        } else if (auth()->user()->isStudent())  {
            return view('userProfile.studentProfile.partials.catalogs.view-catalog', compact('catalogData'));
        } else {
            return view('404NotFound');
        }
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

    /**
     * @param PostPutCatalogRequest $request
     * @param University $university
     * @param Catalog $catalog
     * @return JsonResponse
     */
    public function updateCatalog(PostPutCatalogRequest $request, University $university, Catalog $catalog): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->catalogService->updateCatalog($catalog, $request->all());
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
        ])->setStatusCode(200);
    }

    /**
     * @param University $university
     * @param Catalog $catalog
     * @param CatalogTopic $catalogTopic
     * @return JsonResponse
     */
    public function sendRequestTopic(University $university, Catalog $catalog, CatalogTopic $catalogTopic): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->catalogService->sendRequestTopic($catalogTopic, auth()->user()->getStudent());
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
        ])->setStatusCode(200);
    }

    /**
     * @param CatalogTopic $catalogTopic
     * @return JsonResponse
     */
    public function getTopicRequests(CatalogTopic $catalogTopic): JsonResponse
    {
        return response()->json([
            'message' => 'Success',
            'data' => $this->catalogTopicRepository->export($catalogTopic, ['requests', 'student']),
        ])->setStatusCode(200);
    }

    /**
     * @param TopicRequest $topicRequest
     * @return JsonResponse
     */
    public function approveRequest(TopicRequest $topicRequest): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->catalogService->approveRequest($topicRequest);
        } catch (ServiceUserException $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Can`t approve student topic request. Error: "' . $e->getMessage() . '".',
            ])->setStatusCode(500);
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
        ])->setStatusCode(200);
    }

    /**
     * @param TopicRequest $topicRequest
     * @return JsonResponse
     */
    public function rejectRequest(TopicRequest $topicRequest): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->catalogService->rejectRequest($topicRequest);
        } catch (ServiceUserException $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Can`t reject student topic request. Error: "' . $e->getMessage() . '".',
            ])->setStatusCode(500);
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
        ])->setStatusCode(200);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getSearchParams(Request $request): array
    {
        $searchParams = [];
        if ($request->has('teacherId')) {
            $searchParams['teacher_id'] = $request->get('teacherId');
        }

        if ($request->has('studentId')) {
            $searchParams['student_id'] = $request->get('studentId');
        }

        return $searchParams;
    }
}
