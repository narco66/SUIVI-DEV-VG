<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Action extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'strategic_axis_id',
        'title',
        'code',
        'description',
        'start_date',
        'end_date',
        'priority',
        'status',
        'progress_rate',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'progress_rate' => 'decimal:2',
    ];

    public function strategicAxis()
    {
        return $this->belongsTo(StrategicAxis::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
