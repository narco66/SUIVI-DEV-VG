<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('decision_id')->nullable()->after('documentable_id')->constrained('decisions')->nullOnDelete();
            $table->foreignId('document_category_id')->nullable()->after('category')->constrained('document_categories')->nullOnDelete();
            $table->foreignId('document_status_id')->nullable()->after('document_category_id')->constrained('document_statuses')->nullOnDelete();
            $table->foreignId('confidentiality_level_id')->nullable()->after('document_status_id')->constrained('confidentiality_levels')->nullOnDelete();
            $table->foreignId('storage_location_id')->nullable()->after('confidentiality_level_id')->constrained('storage_locations')->nullOnDelete();
            $table->foreignId('retention_rule_id')->nullable()->after('storage_location_id')->constrained('retention_rules')->nullOnDelete();
            $table->string('reference')->nullable()->after('title');
            $table->text('description')->nullable()->after('reference');
            $table->string('language', 10)->nullable()->after('description');
            $table->string('author_service')->nullable()->after('language');
            $table->date('document_date')->nullable()->after('author_service');
            $table->date('archived_at')->nullable()->after('uploaded_at');
            $table->string('version_label')->default('1.0')->after('archived_at');
            $table->unsignedInteger('version_number')->default(1)->after('version_label');
            $table->boolean('is_major_version')->default(true)->after('version_number');
            $table->boolean('is_current')->default(true)->after('is_major_version');
            $table->boolean('is_main')->default(false)->after('is_current');
            $table->boolean('is_physical_archive')->default(false)->after('is_main');
            $table->string('source_type')->nullable()->after('is_physical_archive');
            $table->string('origin')->nullable()->after('source_type');
            $table->string('mime_type')->nullable()->after('type');
            $table->string('hash_sha256', 64)->nullable()->after('mime_type');
            $table->string('storage_disk')->nullable()->after('path');
            $table->string('original_filename')->nullable()->after('storage_disk');
            $table->string('workflow_status')->default('draft')->after('hash_sha256');
            $table->timestamp('validated_at')->nullable()->after('workflow_status');
            $table->foreignId('validated_by')->nullable()->after('validated_at')->constrained('users')->nullOnDelete();

            $table->index(['decision_id', 'is_current']);
            $table->index(['document_category_id', 'document_status_id']);
            $table->index(['confidentiality_level_id', 'workflow_status']);
            $table->index(['hash_sha256']);
            $table->index(['reference']);
        });

        Schema::create('document_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->unsignedInteger('version_number');
            $table->string('version_label');
            $table->boolean('is_major')->default(true);
            $table->text('change_reason')->nullable();
            $table->string('path');
            $table->string('storage_disk')->default('private');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('hash_sha256', 64)->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['document_id', 'version_number']);
            $table->index(['hash_sha256']);
        });

        Schema::create('document_access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['document_id', 'action']);
            $table->index(['user_id', 'created_at']);
        });

        Schema::create('document_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('document_taggables', function (Blueprint $table) {
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->foreignId('document_tag_id')->constrained('document_tags')->cascadeOnDelete();
            $table->primary(['document_id', 'document_tag_id']);
        });

        Schema::create('document_workflows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->text('comment')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_workflows');
        Schema::dropIfExists('document_taggables');
        Schema::dropIfExists('document_tags');
        Schema::dropIfExists('document_access_logs');
        Schema::dropIfExists('document_versions');

        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex(['decision_id', 'is_current']);
            $table->dropIndex(['document_category_id', 'document_status_id']);
            $table->dropIndex(['confidentiality_level_id', 'workflow_status']);
            $table->dropIndex(['hash_sha256']);
            $table->dropIndex(['reference']);

            $table->dropConstrainedForeignId('validated_by');
            $table->dropConstrainedForeignId('retention_rule_id');
            $table->dropConstrainedForeignId('storage_location_id');
            $table->dropConstrainedForeignId('confidentiality_level_id');
            $table->dropConstrainedForeignId('document_status_id');
            $table->dropConstrainedForeignId('document_category_id');
            $table->dropConstrainedForeignId('decision_id');

            $table->dropColumn([
                'reference',
                'description',
                'language',
                'author_service',
                'document_date',
                'archived_at',
                'version_label',
                'version_number',
                'is_major_version',
                'is_current',
                'is_main',
                'is_physical_archive',
                'source_type',
                'origin',
                'mime_type',
                'hash_sha256',
                'storage_disk',
                'original_filename',
                'workflow_status',
                'validated_at',
            ]);
        });
    }
};

