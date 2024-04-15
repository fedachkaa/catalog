<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostPutStudentRequest;
use App\Models\University;
use App\Models\UserRole;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Services\StudentService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StudentController extends Controller
{
    /** @var StudentRepositoryInterface */
    private $studentRepository;

    /** @var StudentService */
    private $studentService;

    /**
     * @param StudentRepositoryInterface $studentRepository
     * @param StudentService $studentService
     */
    public function __construct(
        StudentRepositoryInterface $studentRepository,
        StudentService $studentService
    ) {
        $this->studentRepository = $studentRepository;
        $this->studentService = $studentService;
    }

    /**
     * @param University $university
     * @return Application|Factory|View
     */
    public function getStudents(University $university)
    {
        if (auth()->user()->isUniversityAdmin()) {
            return view('universityAdminProfile.partials.students.students-block');
        } else if (auth()->user()->isTeacher()) {
            return view('teacherProfile.partials.students.students-block');
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
    public function getStudentsList(Request $request, University $university): JsonResponse
    {
        $searchParams = array_merge(['university_id' => $university->getId()], $this->getSearchParams($request));

        $students = $this->studentRepository->getAll($searchParams);

        return response()->json([
            'message' => 'Success',
            'data' => $this->studentRepository->exportAll($students, ['user', 'faculty', 'course', 'group'])
        ])->setStatusCode(200);
    }

    /**
     * AJAX Route
     *
     * @param PostPutStudentRequest $request
     * @param University $university
     * @return JsonResponse
     */
    public function saveStudent(PostPutStudentRequest $request, University $university): JsonResponse
    {
        try {
            $student = $this->studentService->saveStudent([
                'first_name' => $request->post('first_name'),
                'last_name' => $request->post('last_name'),
                'email' => $request->post('email'),
                'phone_number' => $request->post('phone_number'),
                'group_id' => $request->post('group_id'),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Internal serve error',
                'error' => $e->getMessage()
            ])->setStatusCode(500);
        }

        return response()->json([
            'message' => 'Success',
            'data' => $this->studentRepository->export($student, ['user', 'faculty', 'course', 'group']),
        ])->setStatusCode(200);
    }

    /**
     * @param Request $request
     * @param University $university
     * @return JsonResponse
     */
    public function importStudents(Request $request, University $university): JsonResponse
    {
        if ($request->hasFile('students_file') && $request->file('students_file')->isValid()) {
            $excelFile = $request->file('students_file');

            $spreadsheet = IOFactory::load($excelFile->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $data = $worksheet->toArray();

            $students = [];
            if (!empty($data)) {
                $columns = array_shift($data);

                foreach ($data as $row) {
                    $rowData = [];
                    foreach ($row as $key => $value) {
                        $columnName = $columns[$key];
                        switch ($columnName) {
                            case 'Name':
                                $rowData['first_name'] = $value;
                                break;
                            case 'Surname':
                                $rowData['last_name'] = $value;
                                break;
                            case 'Email':
                                $rowData['email'] = $value;
                                break;
                            case 'Phone':
                                $rowData['phone_number'] = $value;
                                break;
                        }
                    }
                    $rowData['role_id'] = UserRole::USER_ROLE_STUDENT;
                    $rowData['group_id'] = $request->post('group_id');

                    try {
                        $student = $this->studentService->saveStudent($rowData);
                        $students[] = $this->studentRepository->export($student, ['user']);
                    } catch (\Throwable $e) {
                        return response()->json([
                            'message' => 'Error while saving student: "' . $e->getMessage() . '".',
                        ])->setStatusCode(500);
                    }
                }

                return response()->json([
                    'data' => $students,
                    'message' => 'Students were successfully saved!',
                ]);
            }
        }

        return response()->json([
            'message' => 'Empty data',
        ])->setStatusCode(400);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getSearchParams(Request $request): array
    {
        $searchParams = [];
        if ($request->query('group')) {
            $searchParams['groupTitle'] = $request->query('group');
        }

        if ($request->query('groupId')) {
            $searchParams['group_id'] = $request->query('groupId');
        }

        if ($request->query('surname')) {
            $searchParams['surname'] = $request->query('surname');
        }

        if ($request->query('course')) {
            $searchParams['courseTitle'] = $request->query('course');
        }

        if ($request->query('courseId')) {
            $searchParams['course_id'] = $request->query('courseId');
        }

        if ($request->query('faculty')) {
            $searchParams['faculty_id'] = $request->query('faculty_id');
        }

        if ($request->query('email')) {
            $searchParams['email'] = $request->query('email');
        }

        return $searchParams;
    }
}
