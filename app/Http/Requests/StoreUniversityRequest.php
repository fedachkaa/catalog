<?php

namespace App\Http\Requests;

use App\Models\University;
use Illuminate\Foundation\Http\FormRequest;

class StoreUniversityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'name' => 'required|string|max:150',
            'city' => 'required|string|max:70',
            'address' => 'required|string|max:128',
            'phone_number' => 'required|regex:/^\+?\d{1,15}$/',
            'email' => 'required|email|unique:universities',
            'accreditation_level' => 'required|in:' . implode(',', array_keys(University::AVAILABLE_ACCREDITATION_LEVELS)),
            'founded_at' => 'required|date|before_or_equal:today',
            'website' => 'string|max:128',
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'name.required' => 'The university name is required.',
            'name.string' => 'The university name must be a string.',
            'name.max' => 'The university name must not exceed 255 characters.',

            'city.required' => 'The city is required.',
            'city.string' => 'The city must be a string.',
            'city.max' => 'The city must not exceed 70 characters.',

            'address.required' => 'The city is required.',
            'address.string' => 'The city must be a string.',
            'address.max' => 'The city must not exceed 128 characters.',

            'phone_number.required' => 'The university phone number is required.',
            'phone_number.regex' => 'Invalid phone number format. Valid characters are "+" followed by 1 to 15 digits.',

            'email.required' => 'The university email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'The email address is already in use.',

            'accreditation_level.required' => 'The accreditation level is required.',
            'accreditation_level.in' => 'Invalid accreditation level value.',

            'founded_at.required' => 'The date of foundation is required.',
            'founded_at.date' => 'Invalid date value.',
            'founded_at.before_or_equal' => 'The date of foundation must not be future.',

            'website.string' => 'The website must be a string.',
            'website.max' => 'The website must not exceed 255 characters.',
        ];
    }
}
