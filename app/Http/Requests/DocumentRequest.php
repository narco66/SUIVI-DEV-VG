<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // La vérification fine est faite dans le contrôleur via DocumentPolicy
        return auth()->check();
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            // Cible polymorphique
            'documentable_type' => ['required_unless:' . ($isUpdate ? 'true,true' : 'false,false'), 'string'],
            'documentable_id'   => ['required_unless:' . ($isUpdate ? 'true,true' : 'false,false'), 'integer'],

            // Identification
            'title'             => ['required', 'string', 'max:255'],
            'reference'         => ['nullable', 'string', 'max:255'],
            'description'       => ['nullable', 'string', 'max:5000'],
            'language'          => ['nullable', 'string', 'max:10'],
            'author_service'    => ['nullable', 'string', 'max:255'],
            'document_date'     => ['nullable', 'date'],

            // Classification
            'category'                 => ['nullable', 'string', 'max:100'],
            'document_category_id'     => ['nullable', 'exists:document_categories,id'],
            'document_status_id'       => ['nullable', 'exists:document_statuses,id'],
            'confidentiality_level_id' => ['nullable', 'exists:confidentiality_levels,id'],
            'retention_rule_id'        => ['nullable', 'exists:retention_rules,id'],
            'storage_location_id'      => ['nullable', 'exists:storage_locations,id'],
            'ged_folder_id'            => ['nullable', 'exists:ged_folders,id'],

            // Origine
            'source_type'       => ['nullable', 'string', 'max:100'],
            'origin'            => ['nullable', 'string', 'max:255'],
            'is_main'           => ['nullable', 'boolean'],
            'is_physical_archive' => ['nullable', 'boolean'],

            // Versioning (pour upload d'une nouvelle version)
            'is_major_version'  => ['nullable', 'boolean'],
            'change_reason'     => ['nullable', 'string', 'max:1000'],

            // Tags
            'tags'              => ['nullable', 'array'],
            'tags.*'            => ['string', 'max:80'],

            // Fichier — obligatoire à la création, optionnel à la mise à jour
            'file' => [
                $isUpdate ? 'nullable' : 'required',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip,odt,ods,odp,txt,csv',
                'max:51200', // 50 Mo
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'                => 'Le titre du document est obligatoire.',
            'title.max'                     => 'Le titre ne peut pas dépasser 255 caractères.',
            'documentable_type.required'    => 'Le type d\'objet associé est obligatoire.',
            'documentable_id.required'      => 'L\'identifiant de l\'objet associé est obligatoire.',
            'documentable_id.integer'       => 'L\'identifiant de l\'objet associé doit être un entier.',
            'document_category_id.exists'   => 'La catégorie documentaire sélectionnée n\'existe pas.',
            'document_status_id.exists'     => 'Le statut documentaire sélectionné n\'existe pas.',
            'confidentiality_level_id.exists' => 'Le niveau de confidentialité sélectionné n\'existe pas.',
            'retention_rule_id.exists'      => 'La règle de conservation sélectionnée n\'existe pas.',
            'storage_location_id.exists'    => 'L\'emplacement de stockage sélectionné n\'existe pas.',
            'ged_folder_id.exists'          => 'Le dossier GED sélectionné n\'existe pas.',
            'document_date.date'            => 'La date du document n\'est pas valide.',
            'tags.array'                    => 'Les mots-clés doivent être envoyés sous forme de liste.',
            'tags.*.string'                 => 'Chaque mot-clé doit être une chaîne de caractères.',
            'tags.*.max'                    => 'Chaque mot-clé ne peut pas dépasser 80 caractères.',
            'file.required'                 => 'Veuillez sélectionner un fichier à téléverser.',
            'file.file'                     => 'Le fichier téléversé est invalide.',
            'file.mimes'                    => 'Format de fichier non autorisé. Formats acceptés : PDF, Word, Excel, PowerPoint, Images, ZIP.',
            'file.max'                      => 'Le fichier ne peut pas dépasser 50 Mo.',
            'change_reason.max'             => 'Le motif de modification ne peut pas dépasser 1000 caractères.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title'                     => 'titre',
            'reference'                 => 'référence',
            'description'               => 'description',
            'language'                  => 'langue',
            'author_service'            => 'auteur / service',
            'document_date'             => 'date du document',
            'document_category_id'      => 'catégorie',
            'document_status_id'        => 'statut',
            'confidentiality_level_id'  => 'niveau de confidentialité',
            'retention_rule_id'         => 'règle de conservation',
            'ged_folder_id'             => 'dossier GED',
            'file'                      => 'fichier',
            'change_reason'             => 'motif de modification',
        ];
    }
}
