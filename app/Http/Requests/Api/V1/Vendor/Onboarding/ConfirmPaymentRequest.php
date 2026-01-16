<?php

namespace App\Http\Requests\Api\V1\Vendor\Onboarding;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vendor_subscription_id' => [
                'required',
                'exists:vendor_package_assignments,id',
                function ($attribute, $value, $fail) {
                    // Verify the assignment belongs to the authenticated vendor
                    $vendor = $this->user();
                    if ($vendor) {
                        $exists = \App\Models\VendorPackageAssignment::where('id', $value)
                            ->where('vendor_id', $vendor->id)
                            ->exists();
                        
                        if (!$exists) {
                            $fail(__('vendor.errors.subscription_not_found'));
                        }
                    }
                },
            ],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'simulate_status' => ['nullable', 'in:paid,pending,failed'],
            'reference' => ['nullable', 'string', 'max:255'],
            'meta' => ['nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'vendor_subscription_id.required' => __('validation.required', ['attribute' => __('vendor.fields.subscription')]),
            'vendor_subscription_id.exists' => __('validation.exists', ['attribute' => __('vendor.fields.subscription')]),
            'payment_method_id.required' => __('validation.required', ['attribute' => __('vendor.fields.payment_method')]),
            'payment_method_id.exists' => __('validation.exists', ['attribute' => __('vendor.fields.payment_method')]),
            'simulate_status.in' => __('validation.in', ['attribute' => __('vendor.fields.payment_status')]),
        ];
    }
}
