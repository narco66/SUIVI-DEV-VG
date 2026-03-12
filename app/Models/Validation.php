<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Validation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'update_id',
        'validator_id',
        'status',
        'level',
        'comment',
        'validated_at',
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    public function progressUpdate()
    {
        return $this->belongsTo(ProgressUpdate::class, 'update_id');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validator_id');
    }
}
