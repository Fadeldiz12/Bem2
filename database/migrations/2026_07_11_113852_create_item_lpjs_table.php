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
        Schema::create('item_lpj', function (Blueprint $table) {
            $table->id(
                'ID_Item_LPJ'
            );
            $table->unsignedInteger('ID_Sie');
            $table->unsignedInteger('ID_Bon');
            $table->string('Jenis_Pengeluaran');
            $table->string('Keterangan');
            $table->integer('Qty_Realisasi');
            $table->string('Satuan_Realisasi', 100);
            $table->decimal('Harga_Realisasi', 15, 2);
            $table->decimal('Total_Realisasi', 15, 2)->storedAs('Qty_Realisasi * Harga_Realisasi');
            $table->timestamps();

            // FK biasa ke Sie: setiap Item wajib punya Sie.
            $table->foreign('ID_Sie')
                ->references('ID_Sie')->on('Sie')
                ->onDelete('cascade');

            // Composite FK: (ID_Bon, ID_Sie) di Item harus cocok dengan
            // (ID_Bon, ID_Sie) di Bon. Ini yang memaksa aturan "Item hanya
            // boleh nempel ke Bon yang Sie-nya sama". Kalau ID_Bon NULL,
            // constraint ini otomatis tidak dicek (item belum punya bon).
            $table->foreign(['ID_Bon', 'ID_Sie'])
                ->references(['ID_Bon', 'ID_Sie'])->on('Bon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_lpj');
    }
};
