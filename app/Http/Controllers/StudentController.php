<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Faculty;
use App\Models\Group;
use App\Models\University;
use App\Models\UserRole;
use App\Repositories\Interfaces\GroupRepositoryInterface;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StudentController extends Controller
{
    /** @var StudentRepositoryInterface */
    private $studentRepository;

    /** @var GroupRepositoryInterface */
    private $groupRepository;

    /** @var UserService */
    private $userService;

    /**
     * @param StudentRepositoryInterface $studentRepository
     * @param GroupRepositoryInterface $groupRepository
     * @param UserService $userService
     */
    public function __construct(
        StudentRepositoryInterface $studentRepository,
        GroupRepositoryInterface $groupRepository,
        UserService $userService
    ) {
        $this->studentRepository = $studentRepository;
        $this->groupRepository = $groupRepository;
        $this->userService = $userService;

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
        $group = $this->groupRepository->getOne(['id' => $group->getId()]);

        return response()->json([
            'message' => 'Success',
            'data' => $this->studentRepository->exportAll($group->getStudents(), ['user']),
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
        $user = $this->userService->createUser([
            'role_id' => UserRole::USER_ROLE_STUDENT,
            'first_name' => $request->post('first_name'),
            'last_name' => $request->post('last_name'),
            'user_email' => $request->post('email'),
            'user_phone_number' => $request->post('phone_number')
        ]);

        try {
            $student = $this->studentRepository->getNew([
                'user_id' => $user->getId(),
                'group_id' => $group->getId(),
            ]);
            $student->saveOrFail();
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

            if (!empty($data)) {
                $columns = array_shift($data);

                $parsedData = [];
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
                    $parsedData[] = $rowData;
                }
                // TODO add saving
                return response()->json(['data' => $parsedData], 200);
            } else {
                return response()->json([
                    'message' => 'Empty data',
                ])->setStatusCode(400);
            }
        }
        return response()->json(['data' => []], 200);
    }
}
