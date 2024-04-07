<?php

namespace App\Http\Requests;

use App\Models\Catalog;
use App\Models\UserRole;
use App\Repositories\Interfaces\CatalogSupervisorRepositoryInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

class PostPutTopicRequest extends FormRequest
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
        /** @var CatalogSupervisorRepositoryInterface $catalogSupervisorRepository */
        $catalogSupervisorRepository = App::get(\App\Repositories\CatalogSupervisor::class);

        /** @var Catalog $catalog */
        $catalog = Route::input('catalogId');
        return [
            'topic' => 'required|string|max:256',
            'teacher_id' => [
                'required',
                function($attribute, $value, $fail) use ($catalogSupervisorRepository, $catalog) {
                    $teacher = $catalogSupervisorRepository->getOne([
                        'catalog_id' => $catalog->getId(),
                        'teacher_id' => $value,
                    ]);

                    if (empty($teacher)) {
                        $fail('Teacher not found');
                    }
                }
            ],
        ];
    }
}
