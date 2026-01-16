<?php

namespace App\Http\Requests\Api\V1\Vendor\Onboarding;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommercialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'commercial_name' => ['required', 'string', 'max:255'],
            'brands' => ['required', 'array', 'min:1'],
            'brands.*' => ['required', 'exists:brands,id'],
            'commercial_register_number' => ['nullable', 'string', 'max:100'],
            'freelance_document_number' => ['nullable', 'string', 'max:100'],
            'bank_account_number' => ['required', 'string', 'max:100'],
            'bank_id' => ['required', 'exists:banks,id'],
            'id_card_file' => ['required', 'file', 'mimes:png,jpg,jpeg,pdf', 'max:4096'],
            'commercial_file' => ['required', 'file', 'mimes:png,jpg,jpeg,pdf', 'max:4096'],
            'accept_terms' => ['required', 'boolean', 'in:true,1'],
        ];
    }

    /**
     * Configure validator instance
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // At least one of commercial_register_number or freelance_document_number must be present
            if (empty($this->commercial_register_number) && empty($this->freelance_document_number)) {
                $validator->errors()->add(
                    'commercial_register_number',
                    __('vendor.errors.commercial_or_freelance_required')
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'commercial_name.required' => __('validation.required', ['attribute' => __('vendor.fields.commercial_name')]),
            'brands.required' => __('validation.required', ['attribute' => __('vendor.fields.brands')]),
            'brands.min' => __('validation.min.array', ['attribute' => __('vendor.fields.brands'), 'min' => 1]),
            'brands.*.exists' => __('validation.exists', ['attribute' => __('vendor.fields.brand')]),
            'bank_id.required' => __('validation.required', ['attribute' => __('vendor.fields.bank')]),
            'bank_id.exists' => __('validation.exists', ['attribute' => __('vendor.fields.bank')]),
            'bank_account_number.required' => __('validation.required', ['attribute' => __('vendor.fields.bank_account_number')]),
            'id_card_file.required' => __('validation.required', ['attribute' => __('vendor.fields.id_card_file')]),
            'id_card_file.mimes' => __('validation.mimes', ['attribute' => __('vendor.fields.id_card_file'), 'values' => 'png, jpg, jpeg, pdf']),
            'id_card_file.max' => __('validation.max.file', ['attribute' => __('vendor.fields.id_card_file'), 'max' => '4MB']),
            'commercial_file.required' => __('validation.required', ['attribute' => __('vendor.fields.commercial_file')]),
            'commercial_file.mimes' => __('validation.mimes', ['attribute' => __('vendor.fields.commercial_file'), 'values' => 'png, jpg, jpeg, pdf']),
            'commercial_file.max' => __('validation.max.file', ['attribute' => __('vendor.fields.commercial_file'), 'max' => '4MB']),
            'accept_terms.required' => __('vendor.errors.terms_not_accepted'),
            'accept_terms.in' => __('vendor.errors.terms_not_accepted'),
        ];
    }
}
