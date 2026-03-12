<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organ extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'level',
        'parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo(Organ::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Organ::class, 'parent_id');
    }
}
