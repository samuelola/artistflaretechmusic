<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
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
              'amount' => 'required|integer',
              'account_number' => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'amount.required' => 'Amount is required', 
            'account_number.required' => 'Account Number is required'
        ];
    }
}
