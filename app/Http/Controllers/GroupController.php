<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostPutGroupRequest;
use App\Models\Group;
use App\Models\University;
use App\Repositories\Interfaces\GroupRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroupController
{
    /** @var GroupRepositoryInterface */
    private $groupRepository;

    /**
     * @param GroupRepositoryInterface $groupRepository
     */
    public function __construct(GroupRepositoryInterface $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    /**
     * @param Request $request
     * @param University $university
     * @return JsonResponse
     */
    public function getGroupsList(Request $request, University $university): JsonResponse
    {
        $searchParams = array_merge($this->getSearchParams($request), ['university_id' => $university->getId()]);
        $groups = $this->groupRepository->getAll($searchParams);

        return response()->json([
            'message' => 'Success',
            'data' => $this->groupRepository->exportAll($groups),
        ])->setStatusCode(200);
    }

    /**
     * AJAX Route
     *
     * @param PostPutGroupRequest $request
     * @param University $university
     * @return JsonResponse
     */
    public function saveGroup(PostPutGroupRequest $request, University $university): JsonResponse
    {
        try {
            /** @var Group $group */
            $group = $this->groupRepository->getNew([
                'course_id' => $request->post('course_id'),
                'title' => $request->post('title'),
            ]);
            $group->saveOrFail();
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Internal serve error',
                'error' => $e->getMessage()
            ])->setStatusCode(500);
        }

        return response()->json([
            'message' => 'Success',
            'data' => $this->groupRepository->export($group),
        ])->setStatusCode(200);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getSearchParams(Request $request): array
    {
        $searchParams = [];

        if ($request->query('courseId')) {
            $searchParams['course_id'] = $request->query('courseId');
        }

        return $searchParams;
    }
}
