<?php

namespace App\Http\Requests\Api\V1\User\Cart;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'item_id' => ['required', 'integer', 'exists:vendor_items,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ];
    }
}
