<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawCoinRequest extends FormRequest
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
            'coin'    => 'required|string',
            'amount'  => 'required|numeric|min:0.0001',
            'address' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'coin.required' => 'Coin is required', 
            'amount.required' => 'Amount is required',
            'address.required' => 'Address is required'
        ];
    }

    

   
}
