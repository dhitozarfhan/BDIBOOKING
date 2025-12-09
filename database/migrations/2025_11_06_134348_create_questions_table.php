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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('reporter_name');//before subject
            $table->text('content');
            $table->string('report_title');//before name
            $table->string('mobile');
            $table->string('email');
            $table->string('identity_number')->nullable();
            $table->string('identity_card_attachment')->nullable();
            $table->string('registration_code')->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
