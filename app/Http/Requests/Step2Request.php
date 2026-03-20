<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Step2Request extends FormRequest
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
    public function rules(): array
    {
        return [
            'role' => 'required|string',
            'ownership_type' => 'required|string',

            'ownership_percentage' => 'nullable|integer|min:1|max:100',

            'co_owners' => 'nullable|array',

            'co_owners.*.name' => 'required_if:ownership_type,co|string',
            'co_owners.*.role' => 'required_if:ownership_type,co|string',
            'co_owners.*.percentage' => 'required_if:ownership_type,co|integer|min:1|max:100',
        ];
    }
}
