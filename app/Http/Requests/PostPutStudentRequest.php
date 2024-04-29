<?php

namespace App\Http\Requests;

use App\Models\UserRole;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\FacultyRepositoryInterface;
use App\Repositories\Interfaces\GroupRepositoryInterface;
use Illuminate\Support\Facades\App;

class PostPutStudentRequest extends PostPutUserRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return in_array(auth()->user()->getRoleId(), [UserRole::USER_ROLE_UNIVERSITY_ADMIN, UserRole::USER_ROLE_ADMIN]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        /** @var FacultyRepositoryInterface $facultyRepository */
        $facultyRepository = App::get(\App\Repositories\Faculty::class);

        /** @var CourseRepositoryInterface $courseRepository */
        $courseRepository = App::get(\App\Repositories\Course::class);

        /** @var GroupRepositoryInterface $groupRepository */
        $groupRepository = App::get(\App\Repositories\Group::class);

        return array_merge(
            parent::rules(),
            [
                'faculty_id' => [
                    'required',
                    function($attribute, $value, $fail) use ($facultyRepository) {
                        $faculty = $facultyRepository->getOne(['id' => $value]);
                        if (!$faculty) {
                            $fail('Selected faculty not found');
                        }
                    }
                ],
                'course_id' => [
                    'required',
                    function($attribute, $value, $fail) use ($courseRepository) {
                        $course = $courseRepository->getOne([
                            'faculty_id' => request()->input('faculty_id'),
                            'id' => $value,
                        ]);
                        if (!$course) {
                            $fail('Selected course not found.');
                        }
                    }
                ],
                'group_id' => [
                    'required',
                    function($attribute, $value, $fail) use ($groupRepository) {
                        $group = $groupRepository->getOne([
                            'faculty_id' => request()->input('faculty_id'),
                            'course_id' => request()->input('course_id'),
                            'id' => $value,
                        ]);
                        if (!$group) {
                            $fail('Selected group not found.');
                        }
                    }
                ]
            ]
        );
    }
}
