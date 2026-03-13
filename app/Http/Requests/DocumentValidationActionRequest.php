<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentValidationActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        // Le motif est obligatoire pour le rejet
        $commentRequired = str_ends_with($this->route()?->getName() ?? '', '.reject')
            ? 'required'
            : 'nullable';

        return [
            'comment' => [$commentRequired, 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'comment.required' => 'Le motif du rejet est obligatoire.',
            'comment.max'      => 'Le commentaire ne peut pas dépasser 2000 caractères.',
        ];
    }
}
