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
        Schema::table('rooms', function (Blueprint $table) {
            $table->foreignId('property_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->string('room_number')->nullable()->after('property_id');
            $table->string('floor')->nullable()->after('room_number');
            $table->enum('status', ['available', 'use', 'maintenance', 'cleaned'])->default('available')->after('floor');
            $table->dropColumn(['name', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->text('description')->nullable()->after('name');
            $table->dropForeign(['property_id']);
            $table->dropColumn(['property_id', 'room_number', 'floor', 'status']);
        });
    }
};
