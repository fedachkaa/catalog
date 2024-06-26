<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostPutSubjectRequest;
use App\Models\Subject;
use App\Models\University;
use App\Repositories\Interfaces\SubjectRepositoryInterface;
use App\Repositories\Interfaces\TeacherSubjectRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    /** @var int */
    const PAGINATION_LIMIT = 10;

    /** @var SubjectRepositoryInterface */
    private $subjectRepository;

    /** @var TeacherSubjectRepositoryInterface */
    private $teacherSubjectRepository;

    /**
     * @param SubjectRepositoryInterface $subjectRepository
     * @param TeacherSubjectRepositoryInterface $teacherSubjectRepository
     */
    public function __construct(
        SubjectRepositoryInterface $subjectRepository,
        TeacherSubjectRepositoryInterface $teacherSubjectRepository
    ){
        $this->subjectRepository = $subjectRepository;
        $this->teacherSubjectRepository = $teacherSubjectRepository;
    }

    /**
     * @param University $university
     * @return Application|Factory|View
     */
    public function getSubjects(University $university)
    {
        if (auth()->user()->isUniversityAdmin()) {
            return view('userProfile.universityAdminProfile.partials.subjects.subjects-block');
        } else if (auth()->user()->isTeacher()) {
            $subjectsData = $this->subjectRepository->exportAll(auth()->user()->getTeacher()->getSubjects()->get());
            return view('userProfile.teacherProfile.partials.subjects.subjects-block', compact('subjectsData'));
        } else {
            return view('404NotFound');
        }
    }

    /**
     * AJAX Route
     *
     * @param Request $request
     * @param University $university
     * @return JsonResponse
     */
    public function getSubjectsList(Request $request, University $university): JsonResponse
    {
        $searchParams = $this->getSearchParams($request);
        $totalSubjects = count($this->subjectRepository->getAll(['university_id' => $university->getId()]));
        $subjects = $this->subjectRepository->getAll(array_merge($searchParams, ['university_id' => $university->getId()]));

        return response()->json([
            'message' => 'Success',
            'data' => [
                'subjects' => $this->subjectRepository->exportAll($subjects, ['teachers']),
                'pagination' => $this->getPagination($searchParams, $totalSubjects),
            ]
        ])->setStatusCode(200);
    }

    /**
     * @param PostPutSubjectRequest $request
     * @param University $university
     * @return JsonResponse
     */
    public function saveSubject(PostPutSubjectRequest $request, University $university): JsonResponse
    {
        try {
            /** @var Subject $subject */
            $subject = $this->subjectRepository->getNew([
                'university_id' => $university->getId(),
                'title' => $request->post('title'),
            ]);

            $subject->saveOrFail();

            if ($request->post('teachersIds')) {
                foreach ($request->post('teachersIds') as $teacherId) {
                    $teacherSubject = $this->teacherSubjectRepository->getNew([
                        'teacher_id' => (int) $teacherId,
                        'subject_id' => $subject->getId(),
                    ]);

                    $teacherSubject->saveOrFail();
                }
            }
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Internal serve error',
                'error' => $e->getMessage()
            ])->setStatusCode(500);
        }

        return response()->json([
            'message' => 'Success',
            'data' => $this->subjectRepository->export($subject, ['teachers']),
        ])->setStatusCode(200);
    }

    /**
     * @param PostPutSubjectRequest $request
     * @param University $university
     * @param Subject $subject
     * @return JsonResponse
     */
    public function updateSubject(PostPutSubjectRequest $request, University $university, Subject $subject) : JsonResponse
    {
        try {
            $subject->updateOrFail([
                'title' => $request->input('title'),
            ]);

            if ($request->input('teachersIds')) {
                $subject->getTeachers()->detach();
                foreach ($request->input('teachersIds') as $teacherId) {
                    $teacherSubject = $this->teacherSubjectRepository->getNew([
                        'teacher_id' => (int) $teacherId,
                        'subject_id' => $subject->getId(),
                    ]);

                    $teacherSubject->saveOrFail();
                }
            }
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Internal serve error',
                'error' => $e->getMessage()
            ])->setStatusCode(500);
        }

        return response()->json([
            'message' => 'Success',
            'data' => $this->subjectRepository->export($subject, ['teachers']),
        ])->setStatusCode(200);
    }

    /**
     * @param University $university
     * @param Subject $subject
     * @return JsonResponse
     */
    public function deleteSubject(University $university, Subject $subject): JsonResponse
    {
        DB::beginTransaction();
        try {
            $subject->delete();
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

        if ($request->has('searchText')) {
            $searchParams['searchText'] = $request->get('searchText');
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
