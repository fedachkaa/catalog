<?php

namespace App\Http\Requests;

use App\Models\Catalog;
use App\Models\UserRole;
use App\Repositories\Interfaces\GroupRepositoryInterface;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class PostPutCatalogRequest extends FormRequest
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
        /** @var TeacherRepositoryInterface $teacherRepository */
        $teacherRepository = App::get(\App\Repositories\Teacher::class);

        /** @var GroupRepositoryInterface $groupRepository */
        $groupRepository = App::get(\App\Repositories\Group::class);

        return [
            'type' => 'required|in:' . implode(',', array_keys(Catalog::AVAILABLE_CATALOG_TYPES)),
            'groupsIds' => [
                function ($attribute, $value, $fail) use ($groupRepository) {
                    foreach ($value as $groupId) {
                        $group = $groupRepository->getOne([
                            'id' => $groupId,
                            'faculty_id' => request()->input('faculty_id'),
                            'course_id' => request()->input('course_id'),
                        ]);
                        if (!$group) {
                            $fail('One or more selected groups do not exist.');
                        }
                    }
                },
            ],
            'teachersIds' => [
                function ($attribute, $value, $fail) use ($teacherRepository) {
                    foreach ($value as $teacherId) {
                        $teacher = $teacherRepository->getOne([
                            'user_id' => $teacherId,
                            'faculty_id' => request()->input('faculty_id'),
                        ]);
                        if (!$teacher) {
                            $fail('One or more selected teachers do not exist.');
                        }
                    }
                },
            ],
        ];
    }
}
