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
        Schema::create('gratifications', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelapor');
            $table->string('nomor_identitas')->nullable();
            $table->text('alamat')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('judul_laporan');
            $table->text('uraian_laporan');
            $table->string('data_dukung')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gratifications');
    }
};