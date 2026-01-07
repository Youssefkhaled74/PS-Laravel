<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class VendorPackageStoreRequest extends FormRequest
{
    public function authorize()
    {
                return Auth::check();

    }

    public function rules()
    {
        return [
            'key' => 'required|string|max:100|unique:vendor_packages,key',
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'monthly_price' => 'required|integer|min:0',
            'yearly_price' => 'required|integer|min:0',
            'currency' => 'nullable|string|max:8',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ];
    }
}
