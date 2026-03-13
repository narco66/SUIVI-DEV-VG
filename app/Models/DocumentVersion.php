<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'version_number',
        'version_label',
        'is_major',
        'change_reason',
        'path',
        'storage_disk',
        'mime_type',
        'size',
        'hash_sha256',
        'uploaded_by',
    ];

    protected $casts = [
        'is_major' => 'boolean',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
