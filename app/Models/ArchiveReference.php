<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchiveReference extends Model
{
    use HasFactory;

    protected $fillable = ['document_id', 'box_code', 'shelf_code', 'room_code', 'physical_reference'];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
