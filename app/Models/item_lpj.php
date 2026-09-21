<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class item_lpj extends Model
{
    protected $table = 'item_lpj';

    protected $primaryKey = 'ID_Item_LPJ';

    protected $fillable = [
        'ID_Sie', 'ID_Bon', 'Jenis_Pengeluaran',
        'Keterangan', 'Qty_Realisasi', 'Satuan_Realisasi', 'Harga_Realisasi',
    ];

    protected $appends = [
        'isNew',
    ];

    // 'Total' adalah generated column (Qty * Harga_Unit) yang dihitung
    // otomatis oleh database, jadi sengaja tidak dimasukkan ke $fillable.

    public function sie()
    {
        return $this->belongsTo(Sie::class, 'ID_Sie', 'ID_Sie');
    }

    public function bon()
    {
        return $this->belongsTo(Bon::class, 'ID_Bon', 'ID_Bon');
    }

    /**
     * Menandai realisasi yang TIDAK ADA padanannya di rencana anggaran
     * awal (Item) — dicocokkan lewat ID_Bon + Jenis_Pengeluaran + Keterangan.
     * Dipakai untuk highlight "item baru" (di luar RAB awal) di halaman LPJ.
     */
    public function getIsNewAttribute() : bool
    {
        $existsInItem = Item::where('ID_Bon', '=', $this->ID_Bon, 'and')
            ->where('Jenis_Pengeluaran', $this->Jenis_Pengeluaran)
            ->where('Keterangan', $this->Keterangan)
            ->exists();

        return ! $existsInItem;
    }

    /**
     * Hubungkan Item ini ke sebuah Bon, dengan validasi bahwa
     * Bon tersebut memang berada di Sie yang sama dengan Item ini.
     * Ini memberi pesan error yang jelas di level aplikasi, sebagai
     * pelengkap composite foreign key yang sudah mengunci ini di database.
     */
    public function attachToBon(Bon $bon): self
    {
        if ($bon->ID_Sie !== $this->ID_Sie) {
            throw new InvalidArgumentException(
                'Bon ini berada di Sie yang berbeda dengan Item, tidak bisa dihubungkan.'
            );
        }

        $this->ID_Bon = $bon->ID_Bon;
        $this->save();

        return $this;
    }

}