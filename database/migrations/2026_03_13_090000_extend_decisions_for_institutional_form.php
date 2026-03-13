<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('decisions', function (Blueprint $table) {
            $table->string('act_type')->nullable()->after('type_id');
            $table->string('official_reference')->nullable()->after('code');
            $table->string('short_title')->nullable()->after('title');
            $table->longText('object')->nullable()->after('summary');
            $table->string('issuing_instance')->nullable()->after('object');
            $table->string('adoption_body')->nullable()->after('issuing_instance');
            $table->string('meeting_edition')->nullable()->after('adoption_body');
            $table->string('adoption_location')->nullable()->after('meeting_edition');
            $table->date('date_signature')->nullable()->after('date_adoption');
            $table->date('date_effective')->nullable()->after('date_signature');
            $table->string('institutional_level')->nullable()->after('institution_id');
            $table->string('geographic_scope')->nullable()->after('institutional_level');
            $table->string('sub_domain')->nullable()->after('domain_id');
            $table->string('legal_status')->nullable()->after('status');
            $table->string('confidentiality_level')->nullable()->after('legal_status');
            $table->longText('context')->nullable()->after('description');
            $table->longText('justification')->nullable()->after('context');
            $table->longText('considerations')->nullable()->after('justification');
            $table->longText('main_provisions')->nullable()->after('considerations');
            $table->longText('key_articles')->nullable()->after('main_provisions');
            $table->longText('expected_results')->nullable()->after('key_articles');
            $table->longText('observations')->nullable()->after('expected_results');
            $table->json('tags')->nullable()->after('observations');
            $table->string('source_language', 10)->nullable()->after('tags');
            $table->json('available_languages')->nullable()->after('source_language');
            $table->foreignId('pilot_department_id')->nullable()->after('created_by')->constrained('departments')->nullOnDelete();
            $table->foreignId('responsible_direction_id')->nullable()->after('pilot_department_id')->constrained('directions')->nullOnDelete();
            $table->foreignId('primary_owner_id')->nullable()->after('responsible_direction_id')->constrained('users')->nullOnDelete();
            $table->json('co_responsible_user_ids')->nullable()->after('primary_owner_id');
            $table->json('member_states')->nullable()->after('co_responsible_user_ids');
            $table->json('stakeholders')->nullable()->after('member_states');
            $table->date('global_deadline')->nullable()->after('date_echeance');
            $table->string('monitoring_mode')->nullable()->after('global_deadline');
            $table->string('initial_execution_status')->nullable()->after('monitoring_mode');
            $table->boolean('requires_action_plan')->default(true)->after('initial_execution_status');
            $table->boolean('requires_indicators')->default(false)->after('requires_action_plan');
            $table->boolean('requires_deliverables')->default(false)->after('requires_indicators');
            $table->boolean('requires_budget')->default(false)->after('requires_deliverables');
            $table->timestamp('submitted_at')->nullable()->after('requires_budget');
            $table->timestamp('last_autosaved_at')->nullable()->after('submitted_at');

            $table->index(['act_type', 'status']);
            $table->index(['date_adoption', 'date_effective']);
            $table->index(['pilot_department_id', 'responsible_direction_id']);
            $table->index('official_reference');
        });
    }

    public function down(): void
    {
        Schema::table('decisions', function (Blueprint $table) {
            $table->dropIndex(['act_type', 'status']);
            $table->dropIndex(['date_adoption', 'date_effective']);
            $table->dropIndex(['pilot_department_id', 'responsible_direction_id']);
            $table->dropIndex(['official_reference']);

            $table->dropConstrainedForeignId('pilot_department_id');
            $table->dropConstrainedForeignId('responsible_direction_id');
            $table->dropConstrainedForeignId('primary_owner_id');

            $table->dropColumn([
                'act_type',
                'official_reference',
                'short_title',
                'object',
                'issuing_instance',
                'adoption_body',
                'meeting_edition',
                'adoption_location',
                'date_signature',
                'date_effective',
                'institutional_level',
                'geographic_scope',
                'sub_domain',
                'legal_status',
                'confidentiality_level',
                'context',
                'justification',
                'considerations',
                'main_provisions',
                'key_articles',
                'expected_results',
                'observations',
                'tags',
                'source_language',
                'available_languages',
                'co_responsible_user_ids',
                'member_states',
                'stakeholders',
                'global_deadline',
                'monitoring_mode',
                'initial_execution_status',
                'requires_action_plan',
                'requires_indicators',
                'requires_deliverables',
                'requires_budget',
                'submitted_at',
                'last_autosaved_at',
            ]);
        });
    }
};

