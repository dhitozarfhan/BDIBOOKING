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
        Schema::create('information', function (Blueprint $table) {
            $table->bigIncrements('information_id');
            $table->integer('category_id');
            $table->timestamp('time_stamp');
            $table->string('file')->nullable();
            $table->year('year')->nullable();
            $table->string('en_title')->nullable();
            $table->string('id_title')->nullable();
            $table->longText('en_summary')->nullable();
            $table->longText('id_summary')->nullable();
            $table->longText('en_content')->nullable();
            $table->longText('id_content')->nullable();
            $table->integer('sort');
            $table->boolean('is_active')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('information');
    }
};
