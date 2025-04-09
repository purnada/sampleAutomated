<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MunicipalityRequest extends FormRequest
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

            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'meta_title' => ['required', 'string'],
            'meta_keyword' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string'],

        ];
        if ($this->getMethod() == 'POST') {
            $rules += [
                'image' => ['required', 'file', 'max:1024', 'mimes:png,jpg,jpeg,svg,webp'],
                'seo_url' => ['required', 'string', 'unique:municipalities'],
            ];
        }
        if ($this->getMethod() == 'PUT') {
            $rules += [
                'image' => ['nullable', 'file', 'max:1024', 'mimes:png,jpg,jpeg,svg,webp'],
                'seo_url' => ['required', 'string', 'unique:municipalities,seo_url,'.$this->municipality->id.',id'],
            ];
        }

        return $rules;
    }
}
