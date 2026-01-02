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
        // Add fields to questions table if not exists
        if (Schema::hasTable('questions')) {
            Schema::table('questions', function (Blueprint $table) {
                if (!Schema::hasColumn('questions', 'registration_code')) {
                    $table->string('registration_code')->unique()->nullable()->after('id');
                }
                if (!Schema::hasColumn('questions', 'status')) {
                    $table->string('status')->default('pending')->after('question');
                }
                if (!Schema::hasColumn('questions', 'answer')) {
                    $table->text('answer')->nullable()->after('status');
                }
                if (!Schema::hasColumn('questions', 'answered_at')) {
                    $table->timestamp('answered_at')->nullable()->after('answer');
                }
            });
        }

        // Add fields to information_requests table if not exists
        if (Schema::hasTable('information_requests')) {
            Schema::table('information_requests', function (Blueprint $table) {
                if (!Schema::hasColumn('information_requests', 'registration_code')) {
                    $table->string('registration_code')->unique()->nullable()->after('id');
                }
                if (!Schema::hasColumn('information_requests', 'status')) {
                    $table->string('status')->default('pending')->after('purpose');
                }
                if (!Schema::hasColumn('information_requests', 'response')) {
                    $table->text('response')->nullable()->after('status');
                }
                if (!Schema::hasColumn('information_requests', 'documents')) {
                    $table->json('documents')->nullable()->after('response');
                }
                if (!Schema::hasColumn('information_requests', 'responded_at')) {
                    $table->timestamp('responded_at')->nullable()->after('documents');
                }
            });
        }

        // Add fields to gratifications table if not exists
        if (Schema::hasTable('gratifications')) {
            Schema::table('gratifications', function (Blueprint $table) {
                if (!Schema::hasColumn('gratifications', 'report_code')) {
                    $table->string('report_code')->unique()->nullable()->after('id');
                }
                if (!Schema::hasColumn('gratifications', 'status')) {
                    $table->string('status')->default('pending')->after('evidence_files');
                }
                if (!Schema::hasColumn('gratifications', 'response')) {
                    $table->text('response')->nullable()->after('status');
                }
                if (!Schema::hasColumn('gratifications', 'responded_at')) {
                    $table->timestamp('responded_at')->nullable()->after('response');
                }
            });
        }

        // Add fields to wbs table if not exists
        if (Schema::hasTable('wbs')) {
            Schema::table('wbs', function (Blueprint $table) {
                if (!Schema::hasColumn('wbs', 'report_code')) {
                    $table->string('report_code')->unique()->nullable()->after('id');
                }
                if (!Schema::hasColumn('wbs', 'status')) {
                    $table->string('status')->default('pending')->after('evidence_files');
                }
                if (!Schema::hasColumn('wbs', 'response')) {
                    $table->text('response')->nullable()->after('status');
                }
                if (!Schema::hasColumn('wbs', 'responded_at')) {
                    $table->timestamp('responded_at')->nullable()->after('response');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove added fields
        if (Schema::hasTable('questions')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->dropColumn(['registration_code', 'status', 'answer', 'answered_at']);
            });
        }

        if (Schema::hasTable('information_requests')) {
            Schema::table('information_requests', function (Blueprint $table) {
                $table->dropColumn(['registration_code', 'status', 'response', 'documents', 'responded_at']);
            });
        }

        if (Schema::hasTable('gratifications')) {
            Schema::table('gratifications', function (Blueprint $table) {
                $table->dropColumn(['report_code', 'status', 'response', 'responded_at']);
            });
        }

        if (Schema::hasTable('wbs')) {
            Schema::table('wbs', function (Blueprint $table) {
                $table->dropColumn(['report_code', 'status', 'response', 'responded_at']);
            });
        }
    }
};
