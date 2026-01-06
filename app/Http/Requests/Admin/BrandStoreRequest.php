<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BrandStoreRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
            'logo' => 'nullable|file|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ];
    }
}
