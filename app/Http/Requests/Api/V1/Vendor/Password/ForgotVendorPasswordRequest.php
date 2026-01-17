<?php

namespace App\Http\Requests\Api\V1\Vendor\Password;

use Illuminate\Foundation\Http\FormRequest;

class ForgotVendorPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => 'required|string|exists:vendors,phone',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => __('api.validation.phone_required'),
            'phone.exists' => __('api.validation.phone_not_found'),
        ];
    }
}
