<?php

namespace App\Http\Requests\Api\V1\User\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutAddressRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'full_name' => ['required','string','max:255'],
            'phone' => ['required','string','max:25'],
            'second_phone' => ['nullable','string','max:25'],
            'city' => ['required','string','max:255'],
            'address_line' => ['required','string','max:255'],
            'lat' => ['nullable','numeric'],
            'lng' => ['nullable','numeric'],
            'is_default' => ['nullable','boolean'],
        ];
    }
}
