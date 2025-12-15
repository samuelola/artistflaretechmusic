<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WalletTransferRequest extends FormRequest
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
              'amount_b' => 'required|integer',
              'recipient' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'amount_b.required' => 'Amount is required', 
            'recipient.required' => 'Recipient is required'
        ];
    }

    
}
