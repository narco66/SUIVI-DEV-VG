<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgressUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'updatable_type' => 'required|string',
            'updatable_id' => 'required|integer',
            'progress_rate' => 'required|numeric|min:0|max:100',
            'achievements' => 'required|string',
            'difficulties' => 'nullable|string',
            'next_steps' => 'nullable|string',
            'support_needs' => 'nullable|string',
        ];
    }
}
