<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Direction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'department_id',
        'name',
        'code',
        'director_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function director()
    {
        return $this->belongsTo(User::class, 'director_id');
    }
}
