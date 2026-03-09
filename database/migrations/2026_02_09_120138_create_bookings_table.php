<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->morphs('bookable'); // bookable_id, bookable_type (Training, Property, etc.)
            $table->enum('booking_type', ['individual', 'batch'])->default('individual');
            $table->unsignedInteger('quantity')->default(1);
            $table->timestamp('start_date')->nullable()->comment('Tanggal mulai pelaksanaan');
            $table->timestamp('end_date')->nullable()->comment('Tanggal berakhir pelaksanaan');
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
