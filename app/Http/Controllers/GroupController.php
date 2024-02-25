<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Faculty;
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
     * AJAX Route
     *
     * @param Request $request
     * @param University $university
     * @param Faculty $faculty
     * @param Course $course
     * @return JsonResponse
     */
    public function saveGroup(Request $request, University $university, Faculty $faculty, Course $course): JsonResponse
    {
        try {
            /** @var Group $group */
            $group = $this->groupRepository->getNew([
                'course_id' => $course->getId(),
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
     * AJAX Route
     *
     * @param University $university
     * @param Faculty $faculty
     * @param Course $course
     * @return JsonResponse
     */
    public function getCourseGroups(University $university, Faculty $faculty, Course $course): JsonResponse
    {
        $groups = $this->groupRepository->getAll([
            'course_id' => $course->getId(),
        ]);
        return response()->json([
            'message' => 'Success',
            'data' => $this->groupRepository->exportAll($groups),
        ])->setStatusCode(200);
    }
}
