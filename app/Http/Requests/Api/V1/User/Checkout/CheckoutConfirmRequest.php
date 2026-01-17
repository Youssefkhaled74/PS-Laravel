<?php

namespace App\Http\Requests\Api\V1\User\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutConfirmRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'note' => ['nullable','string','max:500'],
        ];
    }
}
