<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('decisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->nullable()->constrained('statutory_sessions')->nullOnDelete();
            $table->string('code')->unique();
            $table->string('title', 500);
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();
            $table->string('legal_basis')->nullable();
            
            $table->foreignId('type_id')->constrained('decision_types')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('decision_categories')->nullOnDelete();
            $table->foreignId('domain_id')->nullable()->constrained('domains')->nullOnDelete();
            
            $table->tinyInteger('priority')->default(3); // 1=Critique, 2=Haute, 3=Moyenne, 4=Basse
            
            $table->enum('status', [
                'draft', 'pending_validation', 'active', 'in_progress', 'delayed', 'blocked', 'completed', 'closed', 'cancelled'
            ])->default('draft');
            
            $table->date('date_adoption');
            $table->date('date_echeance')->nullable();
            
            $table->foreignId('institution_id')->nullable()->constrained('institutions')->nullOnDelete();
            $table->decimal('progress_rate', 5, 2)->default(0.00);
            
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('decisions');
    }
};
