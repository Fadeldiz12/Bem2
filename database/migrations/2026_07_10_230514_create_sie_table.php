<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Sie', function (Blueprint $table) {
            $table->increments('ID_Sie');
            $table->unsignedBigInteger('ID_Kegiatan');
            $table->string('Nama_Sie');
            $table->timestamps();

            $table->foreign('ID_Kegiatan')
                  ->references('ID_Kegiatan')->on('Kegiatan')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Sie');
    }
};