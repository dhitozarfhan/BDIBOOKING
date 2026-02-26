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
            $table->dropColumn(['province', 'city']);
            $table->foreignId('province_id')->nullable()->constrained('provinces')->after('address');
            $table->foreignId('city_id')->nullable()->constrained('cities')->after('province_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropForeign(['province_id']);
            $table->dropForeign(['city_id']);
            $table->dropColumn(['province_id', 'city_id']);
            $table->string('province')->nullable()->after('address');
            $table->string('city')->nullable()->after('province');
        });
    }
};
