<?php

namespace App\Http\Requests\Api\V1\Vendor\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyVendorOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => 'required|string|exists:vendors,phone',
            'otp' => 'required|string|size:6',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => __('api.validation.phone_required'),
            'phone.exists' => __('api.validation.phone_not_found'),
            'otp.required' => __('api.validation.otp_required'),
            'otp.size' => __('api.validation.otp_invalid_length'),
        ];
    }
}
