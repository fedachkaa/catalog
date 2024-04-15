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

class SubjectController extends Controller
{
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
            return view('universityAdminProfile.partials.subjects.subjects-block');
        } else if (auth()->user()->isTeacher()) {
            $subjectsData = $this->subjectRepository->exportAll(auth()->user()->getTeacher()->getSubjects());

            return view('teacherProfile.partials.subjects.subjects-block', compact('subjectsData'));
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
        $searchParams = array_merge($this->getSearchParams($request), ['university_id' => $university->getId()]);
        $subjects = $this->subjectRepository->getAll($searchParams);

        return response()->json([
            'message' => 'Success',
            'data' => $this->subjectRepository->exportAll($subjects, ['teachers'])
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
     * @param Request $request
     * @return array
     */
    private function getSearchParams(Request $request): array
    {
        $searchParams = [];

        if ($request->has('searchText')) {
            $searchParams['searchText'] = $request->get('searchText');
        }

        return $searchParams;
    }
}
