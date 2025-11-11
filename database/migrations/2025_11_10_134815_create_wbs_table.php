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
        Schema::create('wbs', function (Blueprint $table) {
            $table->id();
            $table->string('reporter_name');
            $table->string('identity_number')->nullable();
            $table->text('address')->nullable();
            $table->string('occupation')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('report_title');
            $table->text('report_description');
            $table->string('attachment')->nullable();
            $table->foreignId('violation_id')->nullable()->constrained('violations')->onDelete('set null');
            $table->string('registration_code')->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wbs');
    }
};