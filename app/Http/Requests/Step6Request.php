<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Step6Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return  true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'payout_method' => 'required|in:bank,mobile,other',
            'bank_name' => 'required_if:payout_method,bank',
            'account_name' => 'required_if:payout_method,bank,mobile',
            'account_number' => 'required_if:payout_method,bank',
            'country' => 'required_if:payout_method,bank,mobile',
            'mobile_number' => 'required_if:payout_method,mobile',
            'other_info' => 'required_if:payout_method,other',

        ];
    }
}
