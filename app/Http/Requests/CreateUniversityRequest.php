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
                'first_name' => 'required|string|max:70',
                'last_name' => 'required|string|max:70',
                'user_phone_number' => 'required|regex:/^\+?\d{1,15}$/',
                'user_email' => 'required|email',
            ]
        );
    }
}
