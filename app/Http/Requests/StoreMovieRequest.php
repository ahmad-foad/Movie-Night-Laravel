<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'genre' => ['required', 'string', 'max:50'],
            'release_year' => ['required', 'integer', 'min:1888', 'max:'.(date('Y') + 5)],
            'nudity_level' => ['required', 'string', 'in:none,mild,moderate,severe'],
            'violence_level' => ['required', 'string', 'in:none,mild,moderate,severe'],
            'language_level' => ['required', 'string', 'in:none,mild,moderate,severe'],
            'has_clean_edit' => ['sometimes', 'boolean'],
            'clean_edit_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
