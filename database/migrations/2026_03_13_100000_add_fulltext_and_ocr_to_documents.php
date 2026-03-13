<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        $supportsFullText = in_array($driver, ['mysql', 'mariadb', 'pgsql'], true);

        Schema::table('documents', function (Blueprint $table) {
            $table->string('ocr_status')->default('pending')->after('workflow_status');
            $table->longText('ocr_text')->nullable()->after('ocr_status');
            $table->timestamp('ocr_processed_at')->nullable()->after('ocr_text');
        });

        if ($supportsFullText) {
            Schema::table('documents', function (Blueprint $table) {
                $table->fullText(['title', 'reference', 'description'], 'documents_fulltext_idx');
            });

            Schema::table('decisions', function (Blueprint $table) {
                $table->fullText(['title', 'summary', 'object', 'main_provisions'], 'decisions_fulltext_idx');
            });
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        $supportsFullText = in_array($driver, ['mysql', 'mariadb', 'pgsql'], true);

        if ($supportsFullText) {
            Schema::table('decisions', function (Blueprint $table) {
                $table->dropFullText('decisions_fulltext_idx');
            });
        }

        if ($supportsFullText) {
            Schema::table('documents', function (Blueprint $table) {
                $table->dropFullText('documents_fulltext_idx');
            });
        }

        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['ocr_status', 'ocr_text', 'ocr_processed_at']);
        });
    }
};
