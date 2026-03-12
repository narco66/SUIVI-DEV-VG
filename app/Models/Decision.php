<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Decision extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'session_id',
        'code',
        'title',
        'summary',
        'description',
        'legal_basis',
        'type_id',
        'category_id',
        'domain_id',
        'priority',
        'status',
        'date_adoption',
        'date_echeance',
        'institution_id',
        'progress_rate',
        'created_by',
    ];

    protected $casts = [
        'date_adoption' => 'date',
        'date_echeance' => 'date',
        'progress_rate' => 'decimal:2',
    ];

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function type()
    {
        return $this->belongsTo(DecisionType::class, 'type_id');
    }

    public function category()
    {
        return $this->belongsTo(DecisionCategory::class, 'category_id');
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class, 'domain_id');
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function actionPlans()
    {
        return $this->hasMany(ActionPlan::class);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
