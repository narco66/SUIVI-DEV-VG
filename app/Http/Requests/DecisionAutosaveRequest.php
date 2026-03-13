<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DecisionAutosaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'decision_id' => 'nullable|integer|exists:decisions,id',
            'title' => 'nullable|string|max:500',
            'type_id' => 'nullable|exists:decision_types,id',
            'priority' => 'nullable|integer|in:1,2,3,4',
            'status' => 'nullable|string',
            'date_adoption' => 'nullable|date',
            'payload' => 'nullable|array',
        ];
    }
}

