<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Kegiatan', function (Blueprint $table) {
            $table->id('ID_Kegiatan');

            // Relasi ke tabel users (pembuat / penanggung jawab kegiatan).
            // Sebelumnya relasi ini mengarah ke tabel Pengguna terpisah;
            // sekarang users adalah satu-satunya tabel akun di aplikasi ini.
            // nullable + nullOnDelete supaya data Kegiatan tetap aman
            // walau akun user yang membuatnya dihapus.
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->string('Nama_Kegiatan');
            $table->date('Tanggal_Pelaksanaan')->nullable();
            $table->enum('Jenis_RAB', ['Proposal', 'LPJ']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Kegiatan');
    }
};
