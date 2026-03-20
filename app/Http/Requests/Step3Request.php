<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Step3Request extends FormRequest
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
            'songs' => 'required|array',
            'songs.*.title' => 'required|string|max:255',
            'songs.*.artist_name' => 'required|string|max:255',
            'songs.*.release_year' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'songs.*.genre' => 'required|string|max:100',
            'songs.*.duration' => 'required|string|max:10',
            'songs.*.distribution_status' => 'required|in:released,unreleased,previously_distributed',
            'songs.*.spotify_link' => 'nullable|url',
            'songs.*.apple_link' => 'nullable|url',
            'songs.*.audiomack_link' => 'nullable|url',
            'songs.*.youtube_link' => 'nullable|url',
            'files' => 'required|array',
            'files.*' => 'required|file|mimes:mp3,wav|max:102400', // max 100MB
        ];
    }
}
