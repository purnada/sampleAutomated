<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
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
            'doctor_id' => ['required', 'integer'],
            'name' => ['required', 'string'],
            'age' => ['required', 'numeric'],
            'gender' => ['required', 'string'],

            'contact_number' => ['required', 'string'],
            'payment_mode' => ['required', 'string'],
            'appointment_date' => ['required', 'date', 'date_format:Y-m-d H:i'],
            'nepali_date' => ['required', 'date', 'date_format:Y-m-d'],
            'visited_type' => ['required', 'string'],
            'sector' => ['required', 'string'],
            'province_id' => ['nullable', 'integer'],
            'district_id' => ['nullable', 'integer'],
            'municipality_id' => ['nullable', 'integer'],
            'ward_no' => ['nullable', 'string'],
            'house_no' => ['nullable', 'string'],
            'disease' => ['required'],

        ];

        return $rules;
    }
}
