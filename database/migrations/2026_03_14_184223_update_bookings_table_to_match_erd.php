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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('id_booking')->unique()->nullable()->after('id');
            $table->foreignId('assigned_room_id')->nullable()->after('bookable_id')->constrained('rooms')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['assigned_room_id']);
            $table->dropColumn(['id_booking', 'assigned_room_id']);
        });
    }
};
