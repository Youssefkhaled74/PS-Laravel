<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        $id = $this->route('id');
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:191', "unique:admins,email,{$id}"],
            'status' => ['required', 'in:active,inactive'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }
}
