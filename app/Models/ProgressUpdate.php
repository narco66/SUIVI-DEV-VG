<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ProgressUpdate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'updatable_type',
        'updatable_id',
        'progress_rate',
        'achievements',
        'difficulties',
        'next_steps',
        'support_needs',
        'status',
        'author_id',
        'submitted_at',
    ];

    protected $casts = [
        'progress_rate' => 'decimal:2',
        'submitted_at' => 'datetime',
    ];

    public function updatable()
    {
        return $this->morphTo();
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Alias kept for backward compatibility with existing views/controllers.
    public function user()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function validations()
    {
        return $this->hasMany(Validation::class, 'update_id');
    }
}
