<?php

namespace App\Http\Requests\Api\V1\Vendor\Items;

use Illuminate\Foundation\Http\FormRequest;

class StoreVendorItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'required|image|mimes:jpg,jpeg,png|max:4096',
            'category_id' => 'required|exists:categories,id',
            'piece_type_id' => 'required|exists:piece_types,id',
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'gender_id' => 'required|exists:genders,id',
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'quantity_available' => 'required|integer|min:1',
            'quantity_per_client' => 'nullable|integer|min:1',
            'weight' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'discount_ends_at' => 'nullable|date',
            'warranty' => 'nullable|string|max:100',
            'promo_title' => 'nullable|string|max:255',
            'is_taxable' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'images.required' => __('api.validation.images_required'),
        ];
    }
}
