<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Bon', function (Blueprint $table) {
            $table->increments('ID_Bon');
            $table->unsignedInteger('ID_Sie');
            $table->string('Nama_Bon');

            // Opsional: path/nama file foto bukti bon (disimpan lewat
            // storage disk, kolom ini hanya menyimpan lokasinya).
            $table->string('Foto_Bon')->nullable();

            // Sengaja TIDAK ada kolom Total_Kwitansi di sini. Total kwitansi
            // per bon dihitung on-the-fly = SUM(Item.Total) dari semua Item
            // yang ID_Bon-nya sama dengan bon ini (lihat accessor di model
            // Bon.php). Ini supaya total selalu konsisten dengan item-item
            // yang benar-benar terhubung, sesuai contoh tabel LPJ yang kamu
            // kirim (2+ item bisa digabung jadi 1 kwitansi).
            $table->timestamps();

            $table->foreign('ID_Sie')
                  ->references('ID_Sie')->on('Sie')
                  ->onDelete('cascade');

            // Wajib ada: index unik (ID_Bon, ID_Sie) ini dipakai sebagai
            // target composite foreign key dari tabel Item, supaya Item
            // hanya bisa memilih Bon yang Sie-nya sama dengan Sie milik Item.
            $table->unique(['ID_Bon', 'ID_Sie']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Bon');
    }
};