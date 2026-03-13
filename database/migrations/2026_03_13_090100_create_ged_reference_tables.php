<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('document_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('color_class')->nullable();
            $table->timestamps();
        });

        Schema::create('confidentiality_levels', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->unsignedTinyInteger('rank')->default(1);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('storage_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('disk')->default('private');
            $table->string('base_path')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('retention_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedSmallInteger('retention_years')->default(10);
            $table->boolean('is_permanent')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retention_rules');
        Schema::dropIfExists('storage_locations');
        Schema::dropIfExists('confidentiality_levels');
        Schema::dropIfExists('document_statuses');
        Schema::dropIfExists('document_categories');
    }
};

