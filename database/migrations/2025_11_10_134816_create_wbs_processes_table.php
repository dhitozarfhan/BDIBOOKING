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
        Schema::create('wbs_processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wbs_id')->constrained('wbs')->onDelete('cascade');
            $table->foreignId('response_status_id')->default(1)->constrained('response_statuses');
            $table->text('answer')->nullable(); // Answer/response to the report
            $table->string('answer_attachment')->nullable(); // Answer attachment
            $table->boolean('is_completed')->default(false);  // Indicates if the process is completed            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wbs_processes');
    }
};