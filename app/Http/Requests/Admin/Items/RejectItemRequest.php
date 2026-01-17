<?php

namespace App\Http\Requests\Admin\Items;

use Illuminate\Foundation\Http\FormRequest;

class RejectItemRequest extends FormRequest
{
    public function authorize()
    {
        // allow admins (middleware ensures admin auth)
        return $this->user() !== null;
    }

    public function rules()
    {
        return [
            'rejection_reason' => 'required|string|min:3',
        ];
    }
}
