<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SectorRequest extends FormRequest
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
            $rules = [
                'title' => ['required', 'string', 'unique:sectors'],
            ];
        }
        if ($this->getMethod() == 'PUT') {
            $rules = [
                'title' => ['required', 'string', 'unique:sectors,title,'.$this->sector->id.',id'],
            ];
        }

        return $rules;
    }
}
