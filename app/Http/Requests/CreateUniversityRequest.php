<?php

namespace App\Http\Requests;

class CreateUniversityRequest extends StoreUniversityRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return array_merge(
            parent::rules(),
            [
                'user.first_name' => 'required|string|max:70',
                'user.last_name' => 'required|string|max:70',
                'user.phone_number' => 'required|regex:/^\+?\d{1,15}$/',
                'user.email' => 'required|email',
            ]
        );
    }

    /**
     * @return string[]
     */
    public function messages() : array
    {
        return [
            'user.first_name.required' => 'The user first name is required.',
            'user.first_name.string' => 'The user first name must be a string.',
            'user.first_name.max' => 'The user first name must not exceed 70 characters.',

            'user.last_name.required' => 'The user last name is required.',
            'user.last_name.string' => 'The user last name must be a string.',
            'user.last_name.max' => 'The user last name must not exceed 70 characters.',

            'user.phone_number.required' => 'The user phone number is required.',
            'user.phone_number.regex' => 'Invalid phone number format. Valid characters are "+" followed by 1 to 15 digits.',

            'user.email.required' => 'The user email is required.',
            'user.email.email' => 'Please enter a valid email address.',
            'user.email.unique' => 'The email address is already in use.',

        ];
    }
}
