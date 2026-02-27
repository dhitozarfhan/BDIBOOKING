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
            // Drop old province_id and city_id constraints and columns (if they exist)
            // They were created in 2026_02_26_022200, which we deleted. 
            // However, since it is already run in DB, we must drop them via DB schema
            if (Schema::hasColumn('participants', 'province_id')) {
                // Drop foreign key if exists using naming convention or try/catch 
                try {
                    $table->dropForeign(['province_id']);
                } catch (\Exception $e) {}
                try {
                    $table->dropColumn('province_id');
                } catch (\Exception $e) {}
            }
            if (Schema::hasColumn('participants', 'city_id')) {
                try {
                    $table->dropForeign(['city_id']);
                } catch (\Exception $e) {}
                try {
                    $table->dropColumn('city_id');
                } catch (\Exception $e) {}
            }
        });

        Schema::table('participants', function (Blueprint $table) {
            $table->foreignId('province_id')->nullable()->constrained('areas')->after('address');
            $table->foreignId('city_id')->nullable()->constrained('areas')->after('province_id');
        });
        
        // Drop old tables safely
        Schema::dropIfExists('cities');
        Schema::dropIfExists('provinces');
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
            
            // Re-adding string fields as ultimate fallback
            $table->string('province')->nullable()->after('address');
            $table->string('city')->nullable()->after('province');
        });
    }
};
