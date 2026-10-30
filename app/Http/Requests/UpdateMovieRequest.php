<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:150'],
            'genre' => ['sometimes', 'required', 'string', 'max:50'],
            'release_year' => ['sometimes', 'required', 'integer', 'min:1888', 'max:'.(date('Y') + 5)],
            'nudity_level' => ['sometimes', 'required', 'string', 'in:none,mild,moderate,severe'],
            'violence_level' => ['sometimes', 'required', 'string', 'in:none,mild,moderate,severe'],
            'language_level' => ['sometimes', 'required', 'string', 'in:none,mild,moderate,severe'],
            'has_clean_edit' => ['sometimes', 'required', 'boolean'],
            'clean_edit_notes' => ['sometimes', 'nullable', 'string', 'max:1000'],
        ];
    }
}
