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
        Schema::table('gratifications', function (Blueprint $table) {
            // Kolom-kolom tambahan yang diperlukan berdasarkan struktur versi lama
            $table->string('status')->default('I')->after('data_dukung'); // I=Inisiasi, P=Proses, D=Disposisi, T=Terminasi
            $table->text('jawaban')->nullable()->after('status');
            $table->string('kode_register')->nullable()->after('jawaban');
            $table->timestamp('waktu_publish')->nullable()->after('kode_register');
            $table->string('subject')->nullable()->after('judul_laporan'); // Untuk menyesuaikan dengan struktur versi lama
            $table->text('content')->nullable()->after('subject'); // Untuk menyesuaikan dengan struktur versi lama
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gratifications', function (Blueprint $table) {
            $table->dropColumn(['status', 'jawaban', 'kode_register', 'waktu_publish', 'subject', 'content']);
        });
    }
};
