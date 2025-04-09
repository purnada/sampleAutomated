<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DistrictRequest extends FormRequest
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

            'province_id' => ['required', 'integer'],

        ];
        if ($this->getMethod() == 'POST') {
            $rules += [

                'title' => ['required', 'string', 'unique:districts'],
                'name_nep' => ['required', 'string', 'unique:districts'],
            ];
        }
        if ($this->getMethod() == 'PUT') {
            $rules += [

                'title' => ['required', 'string', 'unique:districts,title,'.$this->district->id.',id'],
                'name_nep' => ['required', 'string', 'unique:districts,name_nep,'.$this->district->id.',id'],
            ];
        }

        return $rules;
    }
}
