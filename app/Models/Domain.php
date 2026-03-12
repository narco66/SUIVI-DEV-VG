<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Domain extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo(Domain::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Domain::class, 'parent_id');
    }

    public function decisions()
    {
        return $this->hasMany(Decision::class);
    }
}
