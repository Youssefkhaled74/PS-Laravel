<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $allowed = implode(',', config('uploads.allowed_extensions', ['jpg','jpeg','png','gif','webp']));
        $max = config('uploads.max_kb', 5120);

        return [
            'name' => ['sometimes', 'string', 'max:191'],
            'email' => ['sometimes', 'email', 'max:191'],
            'avatar' => ['sometimes', 'nullable', 'file', 'mimes:' . $allowed, 'max:' . $max],
            'gallery' => ['sometimes', 'nullable', 'array'],
            'gallery.*' => ['file', 'mimes:' . $allowed, 'max:' . $max],
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = response()->json([
            'success' => false,
            'message' => __('api.validation_failed'),
            'data' => null,
            'errors' => $validator->errors(),
            'meta' => null,
        ], 422);

        throw new \Illuminate\Http\Exceptions\HttpResponseException($response);
    }
}
