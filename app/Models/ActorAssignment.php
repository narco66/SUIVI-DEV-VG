<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ActorAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'assignable_type',
        'assignable_id',
        'user_id',
        'institution_id',
        'country_id',
        'type',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function assignable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
