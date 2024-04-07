<?php

namespace App\Http\Requests;

use App\Models\UserRole;
use App\Repositories\Interfaces\FacultyRepositoryInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class PostPutCourseRequest extends FormRequest
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

        return [
            'course' => 'required|integer|max:6',
            'faculty_id' => [
                'required',
                function($attribute, $value, $fail) use ($facultyRepository) {
                    $faculty = $facultyRepository->getOne(['id' => $value]);
                    if (!$faculty) {
                        $fail('Selected faculty not found');
                    }
                }
            ],
        ];
    }
}
