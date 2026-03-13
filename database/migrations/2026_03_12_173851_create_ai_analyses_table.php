<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('document_type')->nullable(); // décision, résolution...
            $table->string('document_title')->nullable();
            $table->string('document_reference')->nullable();
            $table->longText('source_text');
            $table->longText('prompt_used')->nullable();
            $table->json('raw_response')->nullable();
            $table->json('structured_data')->nullable(); // JSON validé
            $table->string('status')->default('pending'); // pending, validated, rejected, error
            $table->integer('confidence_score')->nullable();
            $table->integer('tokens_used')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_analyses');
    }
};
