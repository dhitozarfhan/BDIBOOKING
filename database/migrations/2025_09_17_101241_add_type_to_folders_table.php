<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('folders', function (Blueprint $table) {
            $table->enum('type', ['bundle', 'lembar'])->after('location_id')->nullable();
        });

        // Set default value for existing records
        DB::table('folders')->whereNull('type')->update(['type' => 'bundle']);
        
        // Make the column non-nullable after setting default values
        Schema::table('folders', function (Blueprint $table) {
            $table->enum('type', ['bundle', 'lembar'])->after('location_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('folders', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
