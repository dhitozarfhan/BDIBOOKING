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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('birth_place');
            $table->date('birth_date');
            $table->foreignId('gender_id')->constrained('genders');
            $table->foreignId('religion_id')->constrained('religions');
            $table->string('blood_type')->nullable(); // A, B, AB, O
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->foreignId('occupation_id')->constrained('occupations');
            $table->foreignId('province_id')->nullable()->constrained('areas');
            $table->foreignId('city_id')->nullable()->constrained('areas');
            $table->foreignId('district_id')->nullable()->constrained('areas');
            $table->foreignId('village_id')->nullable()->constrained('areas');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.y
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
