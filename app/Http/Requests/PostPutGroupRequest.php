<?php

namespace App\Http\Requests;

use App\Models\UserRole;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class PostPutGroupRequest extends FormRequest
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
        /** @var CourseRepositoryInterface $courseRepository */
        $courseRepository = App::get(\App\Repositories\Course::class);

        return [
            'title' => 'required|string|max:255',
            'course_id' => [
                'required',
                function($attribute, $value, $fail) use ($courseRepository) {
                    $course = $courseRepository->getOne([
                        'id' => $value,
                    ]);
                    if (!$course) {
                        $fail('Selected course not found.');
                    }
                }
            ],
        ];
    }
}
