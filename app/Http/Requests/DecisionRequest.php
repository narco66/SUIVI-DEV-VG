<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DecisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $decisionId = $this->route('decision')?->id;
        $isDraft = $this->input('form_action') === 'draft' || $this->input('status') === 'draft';
        $required = $isDraft ? 'nullable' : 'required';

        return [
            'form_action' => ['nullable', Rule::in(['draft', 'submit'])],
            'title' => [$required, 'string', 'max:500'],
            'code' => ['nullable', 'string', 'max:255', Rule::unique('decisions', 'code')->ignore($decisionId)],
            'official_reference' => 'nullable|string|max:255',
            'short_title' => 'nullable|string|max:255',
            'session_id' => 'nullable|exists:statutory_sessions,id',
            'type_id' => [$required, 'exists:decision_types,id'],
            'act_type' => ['nullable', Rule::in([
                'decision', 'reglement', 'directive', 'resolution', 'rapport',
                'compte_rendu', 'communique_final', 'recommandation', 'autre',
            ])],
            'category_id' => 'nullable|exists:decision_categories,id',
            'domain_id' => 'nullable|exists:domains,id',
            'sub_domain' => 'nullable|string|max:255',
            'institution_id' => 'nullable|exists:institutions,id',
            'priority' => [$required, 'integer', 'in:1,2,3,4'],
            'status' => ['nullable', Rule::in([
                'draft', 'pending_validation', 'active', 'in_progress', 'delayed', 'blocked', 'completed', 'closed', 'cancelled',
            ])],
            'decision_status_id' => 'nullable|exists:decision_statuses,id',
            'legal_status' => 'nullable|string|max:120',
            'confidentiality_level' => 'nullable|string|max:120',
            'date_adoption' => [$required, 'date'],
            'date_signature' => 'nullable|date',
            'date_effective' => 'nullable|date',
            'date_echeance' => 'nullable|date|after_or_equal:date_adoption',
            'global_deadline' => 'nullable|date|after_or_equal:date_adoption',
            'summary' => 'nullable|string',
            'object' => 'nullable|string',
            'description' => 'nullable|string',
            'context' => 'nullable|string',
            'justification' => 'nullable|string',
            'legal_basis' => 'nullable|string',
            'considerations' => 'nullable|string',
            'main_provisions' => 'nullable|string',
            'key_articles' => 'nullable|string',
            'expected_results' => 'nullable|string',
            'observations' => 'nullable|string',
            'issuing_instance' => 'nullable|string|max:255',
            'adoption_body' => 'nullable|string|max:255',
            'meeting_edition' => 'nullable|string|max:255',
            'adoption_location' => 'nullable|string|max:255',
            'institutional_level' => 'nullable|string|max:255',
            'geographic_scope' => 'nullable|string|max:255',
            'monitoring_mode' => 'nullable|string|max:120',
            'initial_execution_status' => 'nullable|string|max:120',
            'source_language' => 'nullable|string|max:10',
            'available_languages' => 'nullable|array',
            'available_languages.*' => 'nullable|string|max:10',
            'tags' => 'nullable|array',
            'tags.*' => 'nullable|string|max:80',
            'pilot_department_id' => 'nullable|exists:departments,id',
            'responsible_direction_id' => 'nullable|exists:directions,id',
            'primary_owner_id' => 'nullable|exists:users,id',
            'co_responsible_user_ids' => 'nullable|array',
            'co_responsible_user_ids.*' => 'integer|exists:users,id',
            'member_states' => 'nullable|array',
            'member_states.*' => 'string|max:120',
            'stakeholders' => 'nullable|array',
            'stakeholders.*' => 'string|max:255',
            'requires_action_plan' => 'nullable|boolean',
            'requires_indicators' => 'nullable|boolean',
            'requires_deliverables' => 'nullable|boolean',
            'requires_budget' => 'nullable|boolean',
            'progress_rate' => 'nullable|numeric|min:0|max:100',

            'documents' => 'nullable|array',
            'documents.*' => 'file|max:51200|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip',
            'document_meta' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre officiel est obligatoire.',
            'type_id.required' => 'Le type d’acte est obligatoire.',
            'date_adoption.required' => 'La date d’adoption est obligatoire.',
            'priority.required' => 'La priorité est obligatoire.',
            'documents.*.max' => 'Chaque fichier doit avoir une taille inférieure à 50 Mo.',
            'documents.*.mimes' => 'Format de fichier non autorisé.',
        ];
    }
}
