<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiAnalysis extends Model
{
    protected $fillable = [
        'user_id',
        'document_type',
        'document_title',
        'document_reference',
        'source_text',
        'prompt_used',
        'raw_response',
        'structured_data',
        'status',
        'confidence_score',
        'tokens_used',
    ];

    protected $casts = [
        'raw_response' => 'array',
        'structured_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
