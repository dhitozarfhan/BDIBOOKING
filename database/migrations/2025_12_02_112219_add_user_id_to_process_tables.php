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
        Schema::table('gratification_processes', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->after('response_status_id');
        });

        Schema::table('wbs_processes', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->after('response_status_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gratification_processes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('wbs_processes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};