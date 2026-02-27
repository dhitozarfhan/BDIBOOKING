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
        Schema::table('participants', function (Blueprint $table) {
            $table->foreignId('district_id')->nullable()->constrained('areas')->after('city_id');
            $table->foreignId('village_id')->nullable()->constrained('areas')->after('district_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropForeign(['district_id']);
            $table->dropForeign(['village_id']);
            $table->dropColumn('district_id');
            $table->dropColumn('village_id');
        });
    }
};
