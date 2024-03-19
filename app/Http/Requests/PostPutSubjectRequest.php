<?php

namespace App\Http\Requests;

use App\Models\Teacher;
use App\Models\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class PostPutSubjectRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:256',
            'teachersIds' => [
                function ($attribute, $value, $fail) {
                    $exists = Teacher::where('user_id', $value)->exists();
                    if (!$exists) {
                        $fail('The selected teacher does not exist.');
                    }
                },
            ],
        ];
    }
}
