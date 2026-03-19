<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Step1Request extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'full_name' => 'required|string|max:255',
            'stage_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'nationality' => 'required',
            'country' => 'required',

            'phone' => 'required|string|max:20',
            'email' => 'required|email',

            'youtube' => 'nullable|url',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',

            'id_type' => 'required|string',

            'government_id' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'full_name.required' => 'Full Name is required.',
            'stage_name.required' => 'Stage Name is required.',
            'dob.required' => 'Date of Birth is required.',
            'nationality.required' => 'Nationality is required.',
            'country.required' => 'Country is required.',
            'phone.required' => 'Phone is required.',
            'id_type.required' => 'Select Id type .',
            'email.required' => 'Email is required.',
            'government_id.required' => 'Please upload your ID document.',
        ];
    }
}
