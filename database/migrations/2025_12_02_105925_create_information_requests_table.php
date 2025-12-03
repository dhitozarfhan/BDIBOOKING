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
        Schema::create('information_requests', function (Blueprint $table) {
            $table->id();
            
            // Personal Information
            $table->string('name');
            $table->string('id_card_number');
            $table->text('address');
            $table->string('occupation');
            $table->string('mobile');
            $table->string('email');
            
            // Request Details
            $table->text('content'); // Request details
            $table->text('used_for'); // Purpose of request
            
            // Grab & Delivery Methods (stored as JSON)
            $table->json('grab_method'); // see, read, hear, write, hardcopy, softcopy
            $table->json('delivery_method')->nullable(); // direct, courier, post, fax, email
            
            // Status & Processing is now handled by the ReportProcess model
            
            // Compliance & Security
            $table->boolean('rule_accepted')->default(false);
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('email');
            $table->index('created_at');
            
            //registration_code
            $table->string('registration_code')->nullable()->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('information_requests');
    }
};
