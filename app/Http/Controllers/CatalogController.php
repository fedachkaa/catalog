<?php

namespace App\Http\Controllers;

use App\Events\TopicRequestProcessed;
use App\Exceptions\ServiceUserException;
use App\Http\Requests\PostPutCatalogRequest;
use App\Http\Requests\PostPutTopicRequest;
use App\Models\Catalog;
use App\Models\CatalogTopic;
use App\Models\Topic;
use App\Models\TopicRequest;
use App\Models\University;
use App\Repositories\Interfaces\CatalogRepositoryInterface;
use App\Repositories\Interfaces\CatalogTopicRepositoryInterface;
use App\Repositories\Interfaces\TopicRepositoryInterface;
use App\Services\CatalogService;
use App\Services\OpenAiService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{
    /** @var int */
    const PAGINATION_LIMIT = 10;

    /** @var CatalogRepositoryInterface */
    private $catalogRepository;

    /** @var CatalogTopicRepositoryInterface */
    private $catalogTopicRepository;

    /** @var TopicRepositoryInterface */
    private $topicRepository;

    /** @var CatalogService */
    private $catalogService;

    /** @var OpenAiService */
    private $openAiService;

    /**
     * @param CatalogRepositoryInterface $catalogRepository
     * @param CatalogTopicRepositoryInterface $catalogTopicRepository
     * @param TopicRepositoryInterface $topicRepository
     * @param CatalogService $catalogService
     * @param OpenAiService $openAiService
     */
    public function __construct(
        CatalogRepositoryInterface $catalogRepository,
        CatalogTopicRepositoryInterface $catalogTopicRepository,
        TopicRepositoryInterface $topicRepository,
        CatalogService $catalogService,
        OpenAiService $openAiService
    )
    {
        $this->catalogRepository = $catalogRepository;
        $this->catalogTopicRepository = $catalogTopicRepository;
        $this->topicRepository = $topicRepository;
        $this->catalogService = $catalogService;
        $this->openAiService = $openAiService;
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
        $searchParams = $this->getSearchParams($request);
        $totalCatalogs = count($this->catalogRepository->getAll(['university_id' => $university->getId()]));
        $catalogs = $this->catalogRepository->getAll(array_merge($searchParams, ['university_id' => $university->getId()]));

        return response()->json([
            'message' => 'Success',
            'data' => [
                'catalogs' => $this->catalogRepository->exportAll($catalogs, ['groups', 'supervisors', 'faculty', 'course']),
                'pagination' => $this->getPagination($searchParams, $totalCatalogs),
            ],
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
                'university_id' => $university->getId(),
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
        } else if (auth()->user()->isStudent()) {
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
            /** @var Topic $topic */
            $topic = $this->topicRepository->getNew([
                'teacher_id' => $request->input('teacher_id'),
                'topic' => $request->input('topic'),
            ]);
            $topic->saveOrFail();

            /** @var CatalogTopic $catalogTopic */
            $catalogTopic = $this->catalogTopicRepository->getNew([
                'catalog_id' => $catalog->getId(),
                'topic_id' => $topic->getId(),
            ]);
            $catalogTopic->saveOrFail();
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ])->setStatusCode(500);
        }

        return response()->json([
            'message' => 'Success',
            'data' => $this->catalogTopicRepository->export($catalogTopic, ['topic', 'student']),
        ])->setStatusCode(200);
    }

    /**
     * @param PostPutTopicRequest $request
     * @param University $university
     * @param Catalog $catalog
     * @param Topic $topic
     * @return JsonResponse
     */
    public function updateCatalogTopic(PostPutTopicRequest $request, University $university, Catalog $catalog, Topic $topic): JsonResponse
    {
        try {
            $topic->updateOrFail([
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
     * @param Topic $topic
     * @return JsonResponse
     */
    public function sendRequestTopic(University $university, Catalog $catalog, Topic $topic): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->catalogService->sendRequestTopic($catalog, $topic, auth()->user()->getStudent());
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
            'data' => $this->catalogTopicRepository->export($catalogTopic, ['topic', 'student']),
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

        event(new TopicRequestProcessed($topicRequest));

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

        event(new TopicRequestProcessed($topicRequest));

        return response()->json([
            'message' => 'Success',
        ])->setStatusCode(200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function generateTopics(Request $request): JsonResponse
    {
        $keyword = $request->get('keyword');

        if (empty($keyword)) {
            return response()->json([
                'message' => 'Keyword is required.',
            ])->setStatusCode(400);
        }

        try {
            $data = $this->openAiService->sendRequest($keyword);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Internal server error. Error: "' . $e->getMessage() . '".',
            ])->setStatusCode(500);
        }

        return response()->json([
            'message' => 'Success.',
            'data' => explode("\n\n", $data),
        ])->setStatusCode(200);
    }

    /**
     * @param Request $request
     * @param University $university
     * @param Catalog $catalog
     * @return JsonResponse
     */
    public function saveCatalogAiTopic(Request $request, University $university, Catalog $catalog): JsonResponse
    {
        $topics = $request->input('topics');
        $teacherId = $request->input('teacher_id');

        DB::beginTransaction();
        try {
            if (is_array($topics) && !empty($topics)) {
                foreach ($topics as $topicData) {
                    /** @var Topic $newTopic */
                    $newTopic = $this->topicRepository->getNew([
                        'teacher_id' => $teacherId,
                        'topic' => $topicData['topic'] ?? null,
                        'keyword' => $topicData['keyword'] ?? null,
                        'is_ai_generated' => 1,
                    ]);
                    $newTopic->saveOrFail();

                    /** @var CatalogTopic $catalogTopic */
                    $catalogTopic = $this->catalogTopicRepository->getNew([
                        'catalog_id' => $catalog->getId(),
                        'topic_id' => $newTopic->getId(),
                    ]);

                    $catalogTopic->saveOrFail();
                }
                DB::commit();

                return response()->json([
                    'message' => 'Success'
                ])->setStatusCode(200);
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Cant save topics. Error: "' . $e->getMessage() . '".',
            ])->setStatusCode(500);
        }

        return response()->json([
            'message' => 'Success'
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

        if ($request->has('page')) {
            $searchParams['page'] = (int) $request->get('page');
            $searchParams['limit'] = self::PAGINATION_LIMIT;
            $searchParams['offset'] = ($request->get('page') - 1) * self::PAGINATION_LIMIT;
        } else {
            $searchParams['page'] = 1;
            $searchParams['limit'] = self::PAGINATION_LIMIT;
            $searchParams['offset'] = 0;
        }

        return $searchParams;
    }
}
