<?php

namespace App\Http\Requests;

use App\Models\UserRole;
use App\Repositories\Interfaces\FacultyRepositoryInterface;
use App\Repositories\Interfaces\SubjectRepositoryInterface;
use Illuminate\Support\Facades\App;

class PostPutTeacherRequest extends PostPutUserRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
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
        $facultyRepository = App::get(FacultyRepositoryInterface::class);

        /** @var SubjectRepositoryInterface $subjectRepository */
        $subjectRepository = App::get(SubjectRepositoryInterface::class);

        return array_merge(
            parent::rules(), [
                'faculty_id' => [
                    'required',
                    function($attribute, $value, $fail) use ($facultyRepository) {
                        $faculty = $facultyRepository->getOne(['id' => $value]);
                        if (!$faculty) {
                            $fail('Selected faculty not found');
                        }
                    }
                ],
                'subjectsIds' => [
                    function ($attribute, $value, $fail) use ($subjectRepository) {
                        foreach ($value as $subjectId) {
                            $subject = $subjectRepository->getOne(['id' => $subjectId]);
                            if (!$subject) {
                                $fail('One or more selected subjects do not exist.');
                            }
                        }
                    },
                ],
            ],
        );
    }
}
