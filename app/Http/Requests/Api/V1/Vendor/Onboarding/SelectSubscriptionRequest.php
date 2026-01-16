<?php

namespace App\Http\Requests\Api\V1\Vendor\Onboarding;

use Illuminate\Foundation\Http\FormRequest;

class SelectSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'package_id' => ['required', 'exists:vendor_packages,id'],
            'billing_period' => ['required', 'in:monthly,yearly'],
        ];
    }

    public function messages(): array
    {
        return [
            'package_id.required' => __('validation.required', ['attribute' => __('vendor.fields.package')]),
            'package_id.exists' => __('validation.exists', ['attribute' => __('vendor.fields.package')]),
            'billing_period.required' => __('validation.required', ['attribute' => __('vendor.fields.billing_period')]),
            'billing_period.in' => __('validation.in', ['attribute' => __('vendor.fields.billing_period')]),
        ];
    }
}
