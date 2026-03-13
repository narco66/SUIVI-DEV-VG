<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'documentable_type',
        'documentable_id',
        'decision_id',
        'ged_folder_id',
        'title',
        'reference',
        'description',
        'language',
        'author_service',
        'document_date',
        'archived_at',
        'version_label',
        'version_number',
        'is_major_version',
        'is_current',
        'is_main',
        'is_physical_archive',
        'source_type',
        'origin',
        'type',
        'category',
        'document_category_id',
        'document_status_id',
        'confidentiality_level_id',
        'storage_location_id',
        'retention_rule_id',
        'mime_type',
        'hash_sha256',
        'workflow_status',
        'ocr_status',
        'ocr_text',
        'ocr_processed_at',
        'validated_at',
        'validated_by',
        'path',
        'storage_disk',
        'original_filename',
        'size',
        'uploader_id',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'archived_at' => 'date',
        'document_date' => 'date',
        'is_major_version' => 'boolean',
        'is_current' => 'boolean',
        'is_main' => 'boolean',
        'is_physical_archive' => 'boolean',
        'validated_at' => 'datetime',
        'ocr_processed_at' => 'datetime',
    ];

    public function documentable()
    {
        return $this->morphTo();
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function decision()
    {
        return $this->belongsTo(Decision::class, 'decision_id');
    }

    public function categoryRef()
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }

    public function statusRef()
    {
        return $this->belongsTo(DocumentStatus::class, 'document_status_id');
    }

    public function confidentialityLevel()
    {
        return $this->belongsTo(ConfidentialityLevel::class, 'confidentiality_level_id');
    }

    public function versions()
    {
        return $this->hasMany(DocumentVersion::class);
    }

    public function currentVersion()
    {
        return $this->hasOne(DocumentVersion::class)->latestOfMany('version_number');
    }

    public function accessLogs()
    {
        return $this->hasMany(DocumentAccessLog::class);
    }

    public function tags()
    {
        return $this->belongsToMany(DocumentTag::class, 'document_taggables');
    }

    public function validationFlows()
    {
        return $this->hasMany(DocumentValidationFlow::class);
    }

    public function gedFolder()
    {
        return $this->belongsTo(GedFolder::class, 'ged_folder_id');
    }

    public function archiveReferences()
    {
        return $this->hasMany(ArchiveReference::class);
    }

    public function retentionRule()
    {
        return $this->belongsTo(RetentionRule::class, 'retention_rule_id');
    }

    public function storageLocation()
    {
        return $this->belongsTo(StorageLocation::class, 'storage_location_id');
    }

    public function metadataHistories()
    {
        return $this->hasMany(DocumentMetadataHistory::class);
    }

    public function workflows()
    {
        return $this->hasMany(DocumentWorkflow::class);
    }

    /** Libellé lisible du statut workflow. */
    public function workflowStatusLabel(): string
    {
        return match ($this->workflow_status) {
            'draft'     => 'Brouillon',
            'in_review' => 'En revue',
            'validated' => 'Validé',
            'rejected'  => 'Rejeté',
            'published' => 'Publié',
            'archived'  => 'Archivé',
            'obsolete'  => 'Obsolète',
            default     => ucfirst((string) $this->workflow_status),
        };
    }

    /** Classe Bootstrap pour le badge de statut workflow. */
    public function workflowStatusBadgeClass(): string
    {
        return match ($this->workflow_status) {
            'draft'     => 'bg-secondary',
            'in_review' => 'bg-warning text-dark',
            'validated' => 'bg-success',
            'rejected'  => 'bg-danger',
            'published' => 'bg-primary',
            'archived'  => 'bg-dark',
            'obsolete'  => 'bg-light text-dark border',
            default     => 'bg-light text-dark border',
        };
    }

    /** Taille formatée (Ko / Mo). */
    public function formattedSize(): string
    {
        $bytes = (int) $this->size;
        if ($bytes >= 1_048_576) {
            return number_format($bytes / 1_048_576, 2) . ' Mo';
        }
        if ($bytes >= 1_024) {
            return number_format($bytes / 1_024, 1) . ' Ko';
        }
        return $bytes . ' o';
    }
}
