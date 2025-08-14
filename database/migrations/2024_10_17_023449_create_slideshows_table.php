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
        Schema::create('slideshows', function (Blueprint $table) {
            $table->id();
            $table->integer('link_type_id');
            $table->integer('article_id')->nullable();
            $table->string('image');
            $table->json('title')->nullable();
            $table->json('description')->nullable();
            $table->string('path')->nullable();
            $table->boolean('target_blank')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slideshows');
    }
};
