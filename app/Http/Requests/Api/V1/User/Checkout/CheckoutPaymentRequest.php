<?php

namespace App\Http\Requests\Api\V1\User\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutPaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'payment_method_id' => ['required','integer','exists:payment_methods,id'],
        ];
    }
}
