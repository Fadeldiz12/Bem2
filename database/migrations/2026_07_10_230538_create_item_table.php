<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Item', function (Blueprint $table) {
            $table->increments('ID_Item');
            $table->unsignedInteger('ID_Sie');

            // Nullable: item boleh dibuat dulu (misalnya untuk RAB Proposal)
            // sebelum ada bon/kwitansinya.
            $table->unsignedInteger('ID_Bon')->nullable();

            $table->string('Jenis_Pengeluaran');
            $table->string('Keterangan');
            $table->integer('Qty');
            $table->string('Satuan', 100);
            $table->decimal('Harga_Unit', 15, 2);
            $table->decimal('Total', 15, 2)->storedAs('Qty * Harga_Unit');
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

    public function down(): void
    {
        Schema::dropIfExists('Item');
    }
};