<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseApiRequest extends FormRequest
{
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();
        $message = trans('api.validation_failed') ?: trans('validation.failed');

        $payload = [
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors,
            'meta' => null,
        ];

        throw new HttpResponseException(response()->json($payload, 422));
    }

    /**
     * Handle failed authorization for requests
     */
    protected function failedAuthorization()
    {
        $message = trans('api.unauthorized') ?: trans('auth.unauthenticated');
        $payload = [
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => null,
            'meta' => null,
        ];
        throw new HttpResponseException(response()->json($payload, 403));
    }
}
