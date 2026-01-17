<?php

namespace App\Http\Requests\Api\V1\Vendor\Password;

use Illuminate\Foundation\Http\FormRequest;

class ResetVendorPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => 'required|string|exists:vendors,phone',
            'reset_token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => __('api.validation.phone_required'),
            'phone.exists' => __('api.validation.phone_not_found'),
            'reset_token.required' => __('api.validation.reset_token_required'),
            'password.required' => __('api.validation.password_required'),
            'password.confirmed' => __('api.validation.password_confirmation'),
        ];
    }
}
