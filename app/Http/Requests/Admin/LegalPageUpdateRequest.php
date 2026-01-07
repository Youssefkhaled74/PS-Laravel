<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LegalPageUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'title.en' => ['required', 'string', 'max:255'],
            'title.ar' => ['required', 'string', 'max:255'],
            'content.en' => ['nullable', 'string'],
            'content.ar' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.en.required' => __('validation.required', ['attribute' => __('admin.legal_pages.title_en')]),
            'title.ar.required' => __('validation.required', ['attribute' => __('admin.legal_pages.title_ar')]),
        ];
    }
}
