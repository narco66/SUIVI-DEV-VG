<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentValidationFlow extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'current_level',
        'final_level',
        'status',
        'started_by',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function steps()
    {
        return $this->hasMany(DocumentValidationStep::class, 'flow_id');
    }
}
