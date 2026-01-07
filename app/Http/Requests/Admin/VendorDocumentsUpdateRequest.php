<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class VendorDocumentsUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check();
    }

    public function rules()
    {
        return [
            'id_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf,webp|max:4096',
            'commercial_register' => 'nullable|file|mimes:jpg,jpeg,png,pdf,webp|max:4096',
            'freelance_doc' => 'nullable|file|mimes:jpg,jpeg,png,pdf,webp|max:4096',
        ];
    }
}
