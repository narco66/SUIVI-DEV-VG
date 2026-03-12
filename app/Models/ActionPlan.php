<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActionPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'decision_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function decision()
    {
        return $this->belongsTo(Decision::class);
    }

    public function strategicAxes()
    {
        return $this->hasMany(StrategicAxis::class);
    }
}
