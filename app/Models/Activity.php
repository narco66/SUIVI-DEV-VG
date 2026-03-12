<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'action_id',
        'title',
        'code',
        'description',
        'start_date',
        'end_date',
        'budget',
        'status',
        'progress_rate',
        'direction_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'progress_rate' => 'decimal:2',
    ];

    public function action()
    {
        return $this->belongsTo(Action::class);
    }

    public function direction()
    {
        return $this->belongsTo(Direction::class);
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    public function deliverables()
    {
        return $this->hasMany(Deliverable::class);
    }

    public function progressUpdates()
    {
        return $this->morphMany(ProgressUpdate::class, 'updatable');
    }

    public function indicators()
    {
        return $this->hasMany(Indicator::class);
    }
}
