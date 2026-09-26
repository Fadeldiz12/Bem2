<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Item tambahan di LPJ (kebutuhan mendadak di luar proposal) boleh dicatat dulu
     * tanpa kwitansi, lalu dihubungkan ke Bon belakangan.
     */
    public function up(): void
    {
        Schema::table('item_lpj', function (Blueprint $table) {
            $table->dropForeign(['ID_Bon', 'ID_Sie']);
        });

        Schema::table('item_lpj', function (Blueprint $table) {
            $table->unsignedInteger('ID_Bon')->nullable()->change();
            $table->boolean('Di_Luar_Proposal')->default(false)->after('ID_Bon');

            // FK komposit tidak dicek oleh database selama ID_Bon bernilai NULL.
            $table->foreign(['ID_Bon', 'ID_Sie'])
                ->references(['ID_Bon', 'ID_Sie'])->on('Bon');
        });

        // Tandai item lama yang tidak punya padanan di rencana anggaran (sama dengan
        // aturan accessor isNew sebelumnya: ID_Bon + Jenis_Pengeluaran + Keterangan).
        DB::table('item_lpj')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('Item')
                    ->whereColumn('Item.ID_Bon', 'item_lpj.ID_Bon')
                    ->whereColumn('Item.Jenis_Pengeluaran', 'item_lpj.Jenis_Pengeluaran')
                    ->whereColumn('Item.Keterangan', 'item_lpj.Keterangan');
            })
            ->update(['Di_Luar_Proposal' => true]);
    }

    public function down(): void
    {
        DB::table('item_lpj')->whereNull('ID_Bon')->delete();

        Schema::table('item_lpj', function (Blueprint $table) {
            $table->dropForeign(['ID_Bon', 'ID_Sie']);
        });

        Schema::table('item_lpj', function (Blueprint $table) {
            $table->dropColumn('Di_Luar_Proposal');
            $table->unsignedInteger('ID_Bon')->nullable(false)->change();
            $table->foreign(['ID_Bon', 'ID_Sie'])
                ->references(['ID_Bon', 'ID_Sie'])->on('Bon');
        });
    }
};
