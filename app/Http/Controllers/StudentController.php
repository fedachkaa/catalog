<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Faculty;
use App\Models\Group;
use App\Models\University;
use App\Models\UserRole;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Services\StudentService;
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
     * AJAX Route
     *
     * @param University $university
     * @param Faculty $faculty
     * @param Course $course
     * @param Group $group
     * @return JsonResponse
     */
    public function getGroupStudents(University $university, Faculty $faculty, Course $course, Group $group): JsonResponse
    {
        return response()->json([
            'message' => 'Success',
            'data' => $this->studentService->getStudentsByGroup($group),
        ])->setStatusCode(200);
    }

    /**
     * AJAX Route
     *
     * @param Request $request
     * @param University $university
     * @param Faculty $faculty
     * @param Course $course
     * @param Group $group
     * @return JsonResponse
     * @throws \Exception
     */
    public function saveStudent(Request $request, University $university, Faculty $faculty, Course $course, Group $group): JsonResponse
    {
        try {
            $student = $this->studentService->saveStudent([
                'first_name' => $request->post('first_name'),
                'last_name' => $request->post('last_name'),
                'email' => $request->post('email'),
                'phone_number' => $request->post('phone_number'),
                'group_id' => $group->getId(),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Internal serve error',
                'error' => $e->getMessage()
            ])->setStatusCode(500);
        }

        return response()->json([
            'message' => 'Success',
            'data' => $this->studentRepository->export($student, ['user']),
        ])->setStatusCode(200);
    }

    /**
     * @param Request $request
     * @param University $university
     * @param Faculty $faculty
     * @param Course $course
     * @param Group $group
     * @return JsonResponse
     */
    public function importStudents(Request $request, University $university, Faculty $faculty, Course $course, Group $group): JsonResponse
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
                    $rowData['group_id'] = $group->getId();

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
}
