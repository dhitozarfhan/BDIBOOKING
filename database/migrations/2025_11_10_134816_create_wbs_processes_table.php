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
            $table->timestamp('published_at')->nullable(); // Time the answer was published
            $table->foreignId('disposition_to')->nullable()->constrained('users')->onDelete('set null');
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