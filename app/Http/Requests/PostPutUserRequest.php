<?php

namespace App\Http\Requests;

use App\Models\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class PostPutUserRequest extends FormRequest
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
        return [
            'first_name' => 'required|string|max:70',
            'last_name' => 'required|string|max:70',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|regex:/^\+?\d{1,15}$/',
        ];
    }
}
