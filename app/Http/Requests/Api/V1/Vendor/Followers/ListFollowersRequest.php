<?php

namespace App\Http\Requests\Api\V1\Vendor\Followers;

use Illuminate\Foundation\Http\FormRequest;

class ListFollowersRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'search' => ['nullable', 'string', 'max:100'],
            'per_page' => ['nullable', 'integer', 'min:5', 'max:100'],
        ];
    }
}
