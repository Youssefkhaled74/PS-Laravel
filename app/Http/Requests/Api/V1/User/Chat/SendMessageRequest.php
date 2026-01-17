<?php

namespace App\Http\Requests\Api\V1\User\Chat;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'body' => 'required|string|max:2000',
        ];
    }
}
