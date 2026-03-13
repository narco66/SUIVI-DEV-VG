<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_validation_flows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->unsignedSmallInteger('current_level')->default(1);
            $table->unsignedSmallInteger('final_level')->default(3);
            $table->string('status')->default('in_review');
            $table->foreignId('started_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('document_validation_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flow_id')->constrained('document_validation_flows')->cascadeOnDelete();
            $table->unsignedSmallInteger('level');
            $table->string('validator_role')->nullable();
            $table->foreignId('validator_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('pending');
            $table->foreignId('acted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('acted_at')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['flow_id', 'level'], 'doc_flow_level_unique');
            $table->index(['status', 'validator_role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_validation_steps');
        Schema::dropIfExists('document_validation_flows');
    }
};

