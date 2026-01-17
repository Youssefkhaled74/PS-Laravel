<?php

namespace App\Http\Requests\Api\V1\User\Favorites;

use Illuminate\Foundation\Http\FormRequest;

class ToggleFavoriteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_id' => ['required','integer','exists:vendor_items,id'],
        ];
    }
}
