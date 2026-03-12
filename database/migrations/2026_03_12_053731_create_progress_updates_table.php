<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress_updates', function (Blueprint $table) {
            $table->id();
            $table->morphs('updatable');
            $table->decimal('progress_rate', 5, 2)->default(0.00);
            $table->text('achievements')->nullable();
            $table->text('difficulties')->nullable();
            $table->text('next_steps')->nullable();
            $table->text('support_needs')->nullable();
            $table->string('status')->default('pending'); // pending, validated, rejected
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_updates');
    }
};
