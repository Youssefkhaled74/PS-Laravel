<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorRegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email',
            'phone' => 'required|string|unique:vendors,phone',
            'whatsapp_phone' => 'nullable|string',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'location_text' => 'nullable|string',
            'national_id' => 'nullable|string',
            'password' => 'required|string|confirmed|min:6',
            'accept_terms' => 'accepted',
        ];
    }
}
