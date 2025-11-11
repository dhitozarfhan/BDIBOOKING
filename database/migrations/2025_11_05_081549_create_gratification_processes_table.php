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
        Schema::create('gratification_processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gratification_id')->constrained('gratifications')->onDelete('cascade');
            $table->foreignId('response_status_id')->default(1)->constrained('response_statuses');
            $table->text('answer')->nullable(); // Answer/response to the report
            $table->string('answer_attachment')->nullable(); // Answer attachment similar to attachment in gratifications table
            $table->timestamp('published_at')->nullable(); // Time the answer was published
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gratification_processes');
    }
};
