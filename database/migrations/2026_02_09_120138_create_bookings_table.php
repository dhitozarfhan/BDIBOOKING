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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            // Participant foreign key needs to point to 'participants' table later
            // But since 'participants' migration is created LATER, we need to be careful with foreign keys.
            // For now, let's just use unsignedBigInteger and set foreign key later or assume order is correct if we adjust timestamp.
            // But wait, bookings depends on participants. participants migration is 120221 (later).
            // So foreign key constraint might fail if run in order.
            // I should have created participants first.
            // I will use unsignedBigInteger and index, but maybe delay constraint or just rely on code for now.
            // Actually, migrations run by timestamp.
            // Occupations (120039) -> Trainings (120138) -> Bookings (120138) -> Invoices (120139) -> Participants (120221).
            // This order is WRONG for dependency. Bookings depends on Participants.
            // Solution: Rename migration file for Participants to be earlier.
            
            $table->foreignId('participant_id')->constrained('participants')->onDelete('cascade');
            $table->morphs('bookable'); // bookable_id, bookable_type
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
