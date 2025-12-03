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
        Schema::create('report_processes', function (Blueprint $table) {
            $table->id();
            
            // Menggantikan report_type, gratification_id, wbs_id, question_id, dan information_request_id
            $table->morphs('reportable'); 

            $table->foreignId('response_status_id')->constrained('response_statuses');
            $table->text('answer')->nullable();
            $table->string('answer_attachment')->nullable();
            $table->foreignId('disposition_to_employee_id')->nullable()->constrained('employees')->comment('Employee to whom the report is dispositioned');
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_processes');
    }
};