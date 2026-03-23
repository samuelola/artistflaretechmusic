<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Step4Request extends FormRequest
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
            'data' => 'required|array',
            'data.*.artist_owner_song_id' => 'required|exists:artist_song,id',
            'data.*.contributors' => 'nullable|array',
            'data.*.contributors.*.name' => 'required|string|max:255',
            'data.*.contributors.*.role' => 'required|string|exists:musical_roles,name',
            'data.*.contributors.*.percentage' => 'required|numeric|min:1|max:100',
        ];
    }
}
