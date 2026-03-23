<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Step5Request extends FormRequest
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
            'rights1' => 'required|accepted',
            'rights2' => 'required|accepted',
            'rights3' => 'required|accepted',
            'rights4' => 'required|accepted',
            'rights5' => 'required|accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'rights1.accepted' => 'You must confirm ownership of the recordings.',
            'rights2.accepted' => 'You must confirm no third-party copyright infringement.',
            'rights3.accepted' => 'You must confirm all samples are cleared.',
            'rights4.accepted' => 'You must confirm no legal disputes exist.',
            'rights5.accepted' => 'You must confirm authority to submit these recordings.',
        ];
    }
}
