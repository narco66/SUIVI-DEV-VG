<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statutory_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organ_id')->constrained('organs')->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('type'); // Ordinary, Extraordinary
            $table->date('date_start');
            $table->date('date_end')->nullable();
            $table->string('location')->nullable();
            $table->string('status')->default('planned'); // planned, ongoing, closed
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statutory_sessions');
    }
};
