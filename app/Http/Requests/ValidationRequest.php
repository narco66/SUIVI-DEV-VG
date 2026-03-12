<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Gestion RBAC avec Spatie
    }

    public function rules(): array
    {
        return [
            'update_id' => 'required|exists:progress_updates,id',
            'status' => 'required|in:approved,rejected',
            'comment' => 'nullable|string',
            'level' => 'integer|min:1',
        ];
    }
}
