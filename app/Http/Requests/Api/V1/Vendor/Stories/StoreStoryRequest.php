<?php

namespace App\Http\Requests\Api\V1\Vendor\Stories;

use Illuminate\Foundation\Http\FormRequest;

class StoreStoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'media_type' => ['required', 'in:image,video'],
            'media_file' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,mp4,mov', 'max:51200'],
            'duration_seconds' => ['nullable', 'integer', 'min:1', 'max:60'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'in:active,inactive'],
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date', 'after:start_at'],
        ];
    }
}
