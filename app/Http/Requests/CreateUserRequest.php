<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'first_name'=>'required|string',
            'last_name'=>'required|string',
            'email'=>'required|string|unique:users|max:255',
            'language'=>'required',
            'country'=>'required',
            'state'=>'required',
            'pay_tax' => 'required',
            'terms'=>'required',
            'password' => 'required|string|min:8|confirmed',
           
        ];
    }

     public function messages()
    {
        return [
            'first_name.required' => 'First name is required',
            'first_name.min' => 'First name must not be less than 3 characters.',
            'last_name.required' => 'Last name is required',
            'last_name.min' => 'Last name must not be less than 3 characters.',
            "email.required" => "Email is required",
            "email.unique" => "Email is already taken",
            "language.required" => "Language is required",
            "country.required" => "Country is required",
            "state.required" => "State is required",
            "terms.required" => "Terms field is required",
            "password.min" => "Password must not be less than 8 characters.",
            "pay_tax.required" => "Tax field is required"
        ];
    }
}
