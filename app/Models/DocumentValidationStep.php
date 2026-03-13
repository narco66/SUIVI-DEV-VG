<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentValidationStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'flow_id',
        'level',
        'validator_role',
        'validator_user_id',
        'status',
        'acted_by',
        'acted_at',
        'comment',
    ];

    protected $casts = [
        'acted_at' => 'datetime',
    ];

    public function flow()
    {
        return $this->belongsTo(DocumentValidationFlow::class, 'flow_id');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validator_user_id');
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'acted_by');
    }
}
