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
            $table->string('status')->default('I'); // I=Inisiasi, P=Proses, D=Disposisi, T=Terminasi
            $table->text('jawaban')->nullable(); // Jawaban/respon terhadap laporan
            $table->string('jawaban_lampiran')->nullable(); // Lampiran jawaban similar to data_dukung in gratifications table
            $table->timestamp('waktu_publish')->nullable(); // Waktu publish jawaban
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
