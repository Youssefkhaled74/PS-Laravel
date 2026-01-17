<?php

namespace App\Http\Requests\Api\V1\Vendor\Shipping;

use Illuminate\Foundation\Http\FormRequest;

class ShippingDetailsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'within_city_fee' => 'required|numeric|min:0',
            'within_ksa_fee' => 'required|numeric|min:0',
            'ksa_to_gcc_fee' => 'required|numeric|min:0',
            'ksa_to_world_fee' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:8',
            'status' => 'nullable|in:active,inactive',
        ];
    }
}
