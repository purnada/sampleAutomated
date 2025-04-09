<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
            'email' => ['required', 'email'],
            'analytic' => ['nullable', 'string'],
            'latitude' => ['nullable', 'string'],
            'longitude' => ['nullable', 'string'],
            'detail.*.name' => ['required', 'string'],
            'detail.*.telephone' => ['required', 'string'],
            'detail.*.address' => ['nullable', 'string'],
            'detail.*.meta_title' => ['required', 'string'],
            'detail.*.meta_keyword' => ['nullable', 'string'],
            'detail.*.meta_description' => ['nullable', 'string'],
            'protocol' => ['required', 'string'],
            'parameter' => ['nullable', 'email'],
            'host_name' => ['nullable', 'string'],
            'username' => ['nullable', 'email'],
            'password' => ['nullable', 'string'],
            'smtp_port' => ['nullable', 'integer'],
            'encryption' => ['nullable', 'string'],
            'facebook' => ['nullable', 'url'],
            'twitter' => ['nullable', 'url'],
            'linked_in' => ['nullable', 'url'],
            'youtube' => ['nullable', 'url'],
            'instagram' => ['nullable', 'url'],
            'tiktok' => ['nullable', 'url'],

        ];
        if ($this->getMethod() == 'POST') {
            $rules += [
                'logo' => ['required', 'file', 'max:1024', 'mimes:png,jpg,jpeg,svg,webp'],
                'icon' => ['required', 'file', 'max:1024', 'mimes:png,jpg,jpeg,svg,webp'],
            ];
        }
        if ($this->getMethod() == 'PUT') {
            $rules += [
                'logo' => ['nullable', 'file', 'max:1024', 'mimes:png,jpg,jpeg,svg,webp'],
                'icon' => ['nullable', 'file', 'max:1024', 'mimes:png,jpg,jpeg,svg,webp'],
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'detail.*.name.required' => 'Name Must be required',
            'detail.*.name.string' => 'Name Must be string',
            'detail.*.telephone.required' => 'Telephone Must be required',
            'detail.*.telephone.string' => 'Telephone Must be string',
            'detail.*.address.string' => 'Address Must be string',
            'detail.*.meta_title.required' => 'Meta Title Must be required',
            'detail.*.meta_title.string' => 'Meta Title Must be string',
            'detail.*.meta_keyword.string' => 'Meta Keyword Must be string',
            'detail.*.meta_keyword.string' => 'Meta Description Must be string',
        ];
    }
}
