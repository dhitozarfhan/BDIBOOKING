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
            $table->string('contact_name')->nullable()->after('id_booking');
            $table->string('contact_email')->nullable()->after('contact_name');
            $table->string('contact_phone')->nullable()->after('contact_email');
            $table->string('institution')->nullable()->after('contact_phone');
            $table->decimal('total_price', 15, 2)->nullable()->after('institution');
            $table->string('status')->default('scheduled')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'contact_name', 
                'contact_email', 
                'contact_phone', 
                'institution', 
                'total_price'
            ]);
            $table->string('status')->default('pending')->change();
        });
    }
};
