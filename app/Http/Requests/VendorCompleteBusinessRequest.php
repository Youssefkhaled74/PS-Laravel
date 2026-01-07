<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorCompleteBusinessRequest extends FormRequest
{
    public function authorize()
    {
        return auth('vendor')->check();
    }

    public function rules()
    {
        return [
            'commercial_name' => 'nullable|string|max:255',
            'activity_id' => 'nullable|integer',
            'id_number' => 'nullable|string',
            'commercial_register_number' => 'nullable|string',
            'freelance_doc_number' => 'nullable|string',
            'bank_id' => 'nullable|integer|exists:banks,id',
            'bank_account_number' => 'nullable|string',
            'id_card_image' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'commercial_register_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'freelance_doc_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'accept_terms' => 'accepted',
        ];
    }
}
