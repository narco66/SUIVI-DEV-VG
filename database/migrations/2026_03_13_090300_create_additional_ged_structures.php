<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('archive_references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->string('box_code')->nullable();
            $table->string('shelf_code')->nullable();
            $table->string('room_code')->nullable();
            $table->string('physical_reference')->nullable();
            $table->timestamps();
        });

        Schema::create('ged_folders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('ged_folders')->nullOnDelete();
            $table->string('name');
            $table->string('path')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('ged_folder_id')->nullable()->after('decision_id')->constrained('ged_folders')->nullOnDelete();
        });

        Schema::create('document_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->morphs('relatable');
            $table->string('relation_type')->default('supporting');
            $table->timestamps();
            $table->unique(['document_id', 'relatable_type', 'relatable_id', 'relation_type'], 'doc_rel_unique');
        });

        Schema::create('ged_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->foreignId('shared_with_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('shared_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('document_metadata_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_metadata_histories');
        Schema::dropIfExists('ged_shares');
        Schema::dropIfExists('document_relations');

        Schema::table('documents', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ged_folder_id');
        });

        Schema::dropIfExists('ged_folders');
        Schema::dropIfExists('archive_references');
    }
};

