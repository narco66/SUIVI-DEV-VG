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
        'official_reference',
        'title',
        'short_title',
        'summary',
        'object',
        'description',
        'context',
        'justification',
        'legal_basis',
        'considerations',
        'main_provisions',
        'key_articles',
        'expected_results',
        'observations',
        'tags',
        'source_language',
        'available_languages',
        'type_id',
        'act_type',
        'category_id',
        'domain_id',
        'sub_domain',
        'priority',
        'status',
        'decision_status_id',
        'legal_status',
        'confidentiality_level',
        'issuing_instance',
        'adoption_body',
        'meeting_edition',
        'adoption_location',
        'date_adoption',
        'date_signature',
        'date_effective',
        'date_echeance',
        'global_deadline',
        'monitoring_mode',
        'initial_execution_status',
        'requires_action_plan',
        'requires_indicators',
        'requires_deliverables',
        'requires_budget',
        'institution_id',
        'institutional_level',
        'geographic_scope',
        'pilot_department_id',
        'responsible_direction_id',
        'primary_owner_id',
        'co_responsible_user_ids',
        'member_states',
        'stakeholders',
        'progress_rate',
        'created_by',
        'submitted_at',
        'last_autosaved_at',
    ];

    protected $casts = [
        'date_adoption' => 'date',
        'date_signature' => 'date',
        'date_effective' => 'date',
        'date_echeance' => 'date',
        'global_deadline' => 'date',
        'progress_rate' => 'decimal:2',
        'tags' => 'array',
        'available_languages' => 'array',
        'co_responsible_user_ids' => 'array',
        'member_states' => 'array',
        'stakeholders' => 'array',
        'requires_action_plan' => 'boolean',
        'requires_indicators' => 'boolean',
        'requires_deliverables' => 'boolean',
        'requires_budget' => 'boolean',
        'submitted_at' => 'datetime',
        'last_autosaved_at' => 'datetime',
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

    public function statusRef()
    {
        return $this->belongsTo(DecisionStatus::class, 'decision_status_id');
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

    public function decisionDocuments()
    {
        return $this->hasMany(Document::class, 'decision_id');
    }

    public function actorAssignments()
    {
        return $this->morphMany(ActorAssignment::class, 'assignable');
    }

    public function pilotDepartment()
    {
        return $this->belongsTo(Department::class, 'pilot_department_id');
    }

    public function responsibleDirection()
    {
        return $this->belongsTo(Direction::class, 'responsible_direction_id');
    }

    public function primaryOwner()
    {
        return $this->belongsTo(User::class, 'primary_owner_id');
    }
}
