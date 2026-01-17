<?php

namespace App\Http\Requests\Api\V1\User\SpecialOrders;

use Illuminate\Foundation\Http\FormRequest;

class CreateSpecialOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|integer|exists:categories,id',
            'piece_type_id' => 'required|integer|exists:piece_types,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'color_id' => 'nullable|integer|exists:colors,id',
            'size_id' => 'nullable|integer|exists:sizes,id',
            'gender_id' => 'nullable|integer|exists:genders,id',
            'vendor_id' => 'required|integer|exists:vendors,id',
            'location_text' => 'nullable|string|max:255',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'details' => 'nullable|string|max:2000',
            'urgent' => 'nullable|boolean',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:4096',
        ];
    }
}
