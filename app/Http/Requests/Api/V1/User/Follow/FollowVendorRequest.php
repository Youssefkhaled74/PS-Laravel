<?php

namespace App\Http\Requests\Api\V1\User\Follow;

use Illuminate\Foundation\Http\FormRequest;

class FollowVendorRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'vendor_id' => ['required', 'integer', 'exists:vendors,id'],
        ];
    }
}
