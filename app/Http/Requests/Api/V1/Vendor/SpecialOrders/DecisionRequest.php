<?php

namespace App\Http\Requests\Api\V1\Vendor\SpecialOrders;

use Illuminate\Foundation\Http\FormRequest;

class DecisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'decision' => 'required|in:accept,reject',
            'rejection_reason' => 'required_if:decision,reject|nullable|string|max:500',
        ];
    }
}
