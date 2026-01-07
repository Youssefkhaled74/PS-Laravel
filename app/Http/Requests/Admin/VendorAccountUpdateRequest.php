<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class VendorAccountUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        $vendorId = $this->route('vendor')->id ?? null;
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email,'.$vendorId,
            'phone' => 'nullable|string|unique:vendors,phone,'.$vendorId,
            'whatsapp_phone' => 'nullable|string',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'nullable|in:active,inactive,pending',
        ];
    }
}
