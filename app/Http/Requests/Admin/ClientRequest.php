<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [

            'name' => ['required', 'string'],
            'receipt_no' => ['required', 'string'],
            'detail' => ['nullable', 'string'],
            'contact_no' => ['nullable', 'string'],
            'image' => ['nullable', 'file', 'max:1024', 'mimes:png,jpg,jpeg,svg,webp'],

        ];

        return $rules;
    }
}
