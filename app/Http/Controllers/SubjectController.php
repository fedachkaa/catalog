<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\University;
use App\Repositories\Interfaces\SubjectRepositoryInterface;
use App\Repositories\Interfaces\TeacherSubjectRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SubjectController extends Controller
{
    /** @var SubjectRepositoryInterface */
    private $subjectRepository;

    /** @var TeacherSubjectRepositoryInterface */
    private $teacherSubjectRepository;

    /**
     * @param SubjectRepositoryInterface $subjectRepository
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
     * @return JsonResponse
     */
    public function getSubjects(University $university): JsonResponse
    {
        $subjects = $this->subjectRepository->getAll(['university_id' => $university->getId()]);

        return response()->json([
            'message' => 'Success',
            'data' => $this->subjectRepository->exportAll($subjects, ['teachers'])
        ])->setStatusCode(200);
    }

    /**
     * @param Request $request
     * @param University $university
     * @return JsonResponse
     */
    public function saveSubject(Request $request, University $university): JsonResponse
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
}
