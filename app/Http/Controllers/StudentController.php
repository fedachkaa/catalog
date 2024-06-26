<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Http\Requests\PostPutStudentRequest;
use App\Models\Student;
use App\Models\University;
use App\Models\UserRole;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Services\StudentService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StudentController extends Controller
{
    /** @var int */
    const PAGINATION_LIMIT = 10;

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
            return view('userProfile.universityAdminProfile.partials.students.students-block');
        } else if (auth()->user()->isTeacher()) {
            return view('userProfile.teacherProfile.partials.students.students-block');
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
        $searchParams = $this->getSearchParams($request);
        $totalStudents = count($this->studentRepository->getAll(['university_id' => $university->getId()]));
        $students = $this->studentRepository->getAll(array_merge(['university_id' => $university->getId()], $searchParams));

        return response()->json([
            'message' => 'Success',
            'data' => [
                'students' => $this->studentRepository->exportAll($students, ['user', 'faculty', 'course', 'group']),
                'pagination' => $this->getPagination($searchParams, $totalStudents),
            ]
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

        event(new UserRegistered($student->getUser()));

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
                        $students[] = $this->studentService->saveStudent($rowData);
                    } catch (\Throwable $e) {
                        return response()->json([
                            'message' => 'Error while saving student: "' . $e->getMessage() . '".',
                        ])->setStatusCode(500);
                    }
                }

                $studentsPrepared = [];
                foreach ($students as $student) {
                    $studentsPrepared[] = $this->studentRepository->export($student, ['user']);
                    event(new UserRegistered($student->getUser()));
                }

                return response()->json([
                    'data' => $studentsPrepared,
                    'message' => 'Students were successfully saved!',
                ]);
            }
        }

        return response()->json([
            'message' => 'Empty data',
        ])->setStatusCode(400);
    }

    /**
     * @param University $university
     * @param Student $student
     * @return JsonResponse
     */
    public function editStudent(University $university, Student $student): JsonResponse
    {
        return response()->json([
            'message' => 'Success',
            'data' => $this->studentRepository->export($student, ['user', 'faculty', 'course', 'group']),
        ])->setStatusCode(200);
    }

    /**
     * @param PostPutStudentRequest $request
     * @param University $university
     * @param Student $student
     * @return JsonResponse
     */
    public function updateStudent(PostPutStudentRequest $request, University $university, Student $student): JsonResponse
    {
        DB::beginTransaction();
        try {
            $student = $this->studentService->updateStudent($student, $request->validated());
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
            'data' => $this->studentRepository->export($student, ['user', 'faculty', 'course', 'group']),
        ])->setStatusCode(200);
    }

    /**
     * @param University $university
     * @param Student $student
     * @return JsonResponse
     */
    public function deleteStudent(University $university, Student $student): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->studentService->deleteStudent($student);
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
     * @return Application|Factory|View
     */
    public function getStudentTopicRequests(): View|Factory|Application
    {
        $student = $this->studentRepository->export(auth()->user()->getStudent(), ['topicRequests']);
        $topicRequests = $student['topicRequests'];

        return view('userProfile.studentProfile.partials.requests.requests-block', compact('topicRequests'));
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
