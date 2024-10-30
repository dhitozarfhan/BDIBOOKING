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
        Schema::create('blog', function (Blueprint $table) {
            $table->bigIncrements('blog_id');
            $table->integer('category_id');
            $table->timestamp('time_stamp');
            $table->string('image')->nullable();
            $table->string('en_title')->nullable();
            $table->string('id_title')->nullable();
            $table->text('en_summary')->nullable();
            $table->text('id_summary')->nullable();
            $table->text('en_content')->nullable();
            $table->text('id_content')->nullable();
            $table->integer('hit');
            $table->boolean('is_active')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog');
    }
};
