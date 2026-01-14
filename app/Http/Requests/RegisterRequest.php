<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'full_name' => ['required','string','max:191'],
            'country_code' => ['required','string','max:10'],
            'phone' => ['required','string','max:30'],
            'email' => ['nullable','email','max:191'],
            'password' => ['required','string','min:8','confirmed'],
            'accept_terms' => ['nullable','boolean'],
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
