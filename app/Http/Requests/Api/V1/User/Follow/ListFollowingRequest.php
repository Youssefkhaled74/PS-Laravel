<?php

namespace App\Http\Requests\Api\V1\User\Follow;

use Illuminate\Foundation\Http\FormRequest;

class ListFollowingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:active,muted'],
            'per_page' => ['nullable', 'integer', 'min:5', 'max:100'],
        ];
    }
}
