<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class VendorBusinessUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'commercial_name' => 'required|string|max:255',
            'activity_id' => 'nullable|integer',
            'id_number' => 'nullable|string|max:255',
            'commercial_register_number' => 'nullable|string|max:255',
            'freelance_doc_number' => 'nullable|string|max:255',
            'bank_id' => 'nullable|exists:banks,id',
            'bank_account_number' => 'nullable|string|max:255',
            'status' => 'nullable|in:pending,approved,rejected',
        ];
    }
}
