<?php

namespace App\Http\Requests\Api\V1\User\Chat;

use Illuminate\Foundation\Http\FormRequest;

class CreateConversationRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'vendor_id' => 'required|exists:vendors,id',
        ];
    }
}
