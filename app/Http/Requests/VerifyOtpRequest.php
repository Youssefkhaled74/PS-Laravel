<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // Allow verification either by otp_id OR by phone+country_code
            'otp_id' => ['nullable','integer','required_without:phone'],
            'phone' => ['nullable','string','required_without:otp_id'],
            'country_code' => ['nullable','string'],
            'code' => ['required','digits:6'],
            'purpose' => ['required','in:REGISTER_VERIFY,PASSWORD_RESET'],
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
