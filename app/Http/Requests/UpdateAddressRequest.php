<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'label' => ['nullable','string','max:100'],
            'country' => ['nullable','string','max:100'],
            'city' => ['sometimes','required','string','max:100'],
            'district' => ['nullable','string','max:100'],
            'street' => ['sometimes','required','string','max:191'],
            'building_no' => ['nullable','string','max:50'],
            'apartment_no' => ['nullable','string','max:50'],
            'floor' => ['nullable','string','max:50'],
            'postal_code' => ['nullable','string','max:50'],
            'phone' => ['nullable','string','max:30'],
            'notes' => ['nullable','string'],
            'lat' => ['nullable','numeric'],
            'lng' => ['nullable','numeric'],
            'is_default' => ['nullable','boolean'],
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = response()->json([
            'success' => false,
            'message' => __('api.validation_failed'),
            'data' => null,
            'errors' => $validator->errors(),
            'meta' => null,
        ], 422);

        throw new \Illuminate\Http\Exceptions\HttpResponseException($response);
    }
}
