<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set default value for existing records where type is null
        DB::table('folders')->whereNull('type')->update(['type' => 'bundle']);
        
        // Make the column non-nullable using raw SQL
        DB::statement("ALTER TABLE folders ALTER COLUMN type SET NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Make the column nullable again using raw SQL
        DB::statement("ALTER TABLE folders ALTER COLUMN type DROP NOT NULL");
        
        // Optionally, you can reset the values if needed
        // DB::table('folders')->update(['type' => null]);
    }
};
