<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorChoosePaymentRequest extends FormRequest
{
    public function authorize()
    {
        return auth('vendor')->check();
    }

    public function rules()
    {
        return [
            'vendor_package_assignment_id' => 'nullable|exists:vendor_package_assignments,id',
            'payment_method' => 'required|string',
            'meta' => 'nullable|array',
        ];
    }
}
