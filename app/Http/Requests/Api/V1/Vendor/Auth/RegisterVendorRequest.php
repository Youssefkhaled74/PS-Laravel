<?php

namespace App\Http\Requests\Api\V1\Vendor\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string|min:3|max:255',
            'phone' => 'required|string|unique:vendors,phone',
            'email' => 'nullable|email|unique:vendors,email',
            'second_phone' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
            'avatar' => 'nullable|image|max:2048',
            'national_id' => 'nullable|string|max:50',
            'national_address' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'lat' => 'nullable|numeric|between:-90,90',
            'lng' => 'nullable|numeric|between:-180,180',
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => __('api.validation.full_name_required'),
            'full_name.min' => __('api.validation.full_name_min'),
            'phone.required' => __('api.validation.phone_required'),
            'phone.unique' => __('api.validation.phone_unique'),
            'email.email' => __('api.validation.email_invalid'),
            'email.unique' => __('api.validation.email_unique'),
            'password.required' => __('api.validation.password_required'),
            'password.min' => __('api.validation.password_min'),
            'password.confirmed' => __('api.validation.password_confirmed'),
            'avatar.image' => __('api.validation.avatar_image'),
            'avatar.max' => __('api.validation.avatar_max'),
        ];
    }
}
