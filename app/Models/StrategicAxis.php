<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StrategicAxis extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'action_plan_id',
        'title',
        'code',
        'order',
        'description',
    ];

    public function actionPlan()
    {
        return $this->belongsTo(ActionPlan::class);
    }

    public function actions()
    {
        return $this->hasMany(Action::class);
    }
}
