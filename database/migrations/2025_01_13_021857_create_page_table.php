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
        Schema::create('page', function (Blueprint $table) {
            $table->bigIncrements('page_id');
            $table->string('admin_id', 20)->nullable();
            $table->string('slug', 80)->nullable();
            $table->timestamp('time_stamp');
            $table->string('en_title', 80)->nullable();
            $table->string('id_title', 80)->nullable();
            $table->text('en_summary')->nullable();
            $table->text('id_summary')->nullable();
            $table->text('en_content')->nullable();
            $table->text('id_content')->nullable();
            $table->integer('hit');
            $table->enum('is_active', ['Y', 'N'])->nullable();
            $table->enum('enable_comment', ['Y', 'N'])->nullable();
            $table->enum('auto_accept_comment', ['Y', 'N'])->nullable();
            $table->enum('email_notification_comment', ['Y', 'N'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page');
    }
};
