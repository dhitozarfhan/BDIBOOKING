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
        Schema::create('category', function (Blueprint $table) {
            $table->bigIncrements('category_id');
            $table->integer('core_id')->nullable();
            $table->enum('type', ['news','blog','gallery','event','information','question','request','wbs','service'])->nullable();
            $table->string('en_name')->nullable();
            $table->string('id_name')->nullable();
            $table->integer('sort');
            $table->boolean('is_root')->nullable();
            $table->boolean('is_active')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category');
    }
};
