<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LanguageRequest extends FormRequest
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
        if ($this->getMethod() == 'POST') {
            return [
                'title' => ['required', 'string', 'max:255', 'unique:languages'],
                'code' => ['required', 'string', 'max:255', 'unique:languages'],
            ];
        }

        if ($this->getMethod() == 'PUT') {
            return [
                'title' => ['required', 'string', 'max:255', 'unique:languages,title,'.$this->language->id.',id'],
                'code' => ['required', 'string', 'max:255', 'unique:languages,code,'.$this->language->id.',id'],
            ];
        }

    }
}
