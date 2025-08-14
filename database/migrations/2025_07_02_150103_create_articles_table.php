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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->integer('article_type_id');
            $table->integer('category_id')->nullable();
            $table->integer('author_id')->nullable();
            $table->string('image')->nullable();
            $table->json('title')->nullable();
            $table->json('summary')->nullable();
            $table->json('content')->nullable();
            $table->integer('hit')->default(0);
            $table->boolean('is_active')->default(false);
            $table->dateTime('published_at')->nullable();
            $table->integer('sort')->default(0);
            $table->year('year')->nullable();
            $table->longText('files')->nullable();
            $table->longText('original_files')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
