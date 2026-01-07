<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorChoosePackageRequest extends FormRequest
{
    public function authorize()
    {
        return auth('vendor')->check();
    }

    public function rules()
    {
        return [
            'vendor_package_id' => 'required|exists:vendor_packages,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'starts_at' => 'nullable|date',
        ];
    }
}
