<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DecisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:500',
            'code' => 'nullable|string|max:255|unique:decisions,code,' . ($this->decision ? $this->decision->id : ''),
            'session_id' => 'nullable|exists:statutory_sessions,id',
            'type_id' => 'required|exists:decision_types,id',
            'category_id' => 'nullable|exists:decision_categories,id',
            'domain_id' => 'nullable|exists:domains,id',
            'institution_id' => 'nullable|exists:institutions,id',
            'priority' => 'required|integer|in:1,2,3,4',
            'status' => 'required|string',
            'date_adoption' => 'required|date',
            'date_echeance' => 'nullable|date|after_or_equal:date_adoption',
            'summary' => 'nullable|string',
            'description' => 'nullable|string',
            'legal_basis' => 'nullable|string',
            'progress_rate' => 'nullable|numeric|min:0|max:100',
        ];
    }
}
