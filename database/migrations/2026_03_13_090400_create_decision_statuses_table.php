<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('decision_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('decisions', function (Blueprint $table) {
            $table->foreignId('decision_status_id')->nullable()->after('status')->constrained('decision_statuses')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('decisions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('decision_status_id');
        });
        Schema::dropIfExists('decision_statuses');
    }
};

