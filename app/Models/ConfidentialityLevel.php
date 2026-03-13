<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfidentialityLevel extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'rank', 'description'];
}
