<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Session extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'statutory_sessions';

    protected $fillable = [
        'organ_id',
        'code',
        'type',
        'date_start',
        'date_end',
        'location',
        'status',
    ];

    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
    ];

    public function organ()
    {
        return $this->belongsTo(Organ::class);
    }

    public function decisions()
    {
        return $this->hasMany(Decision::class);
    }
}
