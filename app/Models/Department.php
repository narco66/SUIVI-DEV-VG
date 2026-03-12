<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'institution_id',
        'name',
        'code',
        'commissaire_id',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function directions()
    {
        return $this->hasMany(Direction::class);
    }

    public function commissaire()
    {
        return $this->belongsTo(User::class, 'commissaire_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
