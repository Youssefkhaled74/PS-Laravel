<?php

namespace App\Http\Requests\Api\V1\Vendor\Items;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVendorItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'images' => 'nullable|array|max:5',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'image_ids_to_delete' => 'nullable|array',
            'category_id' => 'nullable|exists:categories,id',
            'piece_type_id' => 'nullable|exists:piece_types,id',
            'name' => 'nullable|string|max:255',
            'brand_id' => 'nullable|exists:brands,id',
            'gender_id' => 'nullable|exists:genders,id',
            'size_id' => 'nullable|exists:sizes,id',
            'color_id' => 'nullable|exists:colors,id',
            'quantity_available' => 'nullable|integer|min:0',
            'quantity_per_client' => 'nullable|integer|min:1',
            'weight' => 'nullable|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'discount_ends_at' => 'nullable|date',
            'warranty' => 'nullable|string|max:100',
            'promo_title' => 'nullable|string|max:255',
            'is_taxable' => 'nullable|boolean',
        ];
    }
}
