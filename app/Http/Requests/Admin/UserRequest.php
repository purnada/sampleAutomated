<?php

namespace App\Http\Requests\Admin;

use App\Rules\ShiftTimeRequired;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
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
            'role' => ['required', 'string'],
            'job' => ['nullable', 'string'],
            'max_booking' => ['nullable', 'integer'],
            'contact_number' => ['nullable', 'string'],
            'sectors' => ['nullable', 'array'],
        ];
        if ($this->getMethod() == 'POST') {
            $rules += [
                'email' => ['required', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'confirmed', Password::min(8)],
                'image' => ['nullable', 'file', 'mimes:png,jpg,jpeg'],
            ];
        }

        if ($this->getMethod() == 'PUT') {
            $rules += [
                'password' => ['nullable', 'string', 'confirmed', Password::min(8)],
                'image' => ['nullable', 'file', 'mimes:png,jpg,jpeg'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$this->user->id.',id'],
            ];
        }
        if ($this->input('role') == 'Doctor') {

            $rules += [
                // 'shift.*.start_time' => ['required','date_format:H:i:s'],
                // 'shift.*.end_time' => ['required','date_format:H:i:s','after:start_time'],
                'shift' => ['required', 'array', new ShiftTimeRequired],
            ];
        }

        return $rules;
    }
}
