<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Bon extends Model
{
    protected $table = 'Bon';
    protected $primaryKey = 'ID_Bon';

    protected $fillable = [
        'ID_Sie', 'Nama_Bon', 'Foto_Bon',
    ];

    // Supaya $bon->total_kwitansi otomatis ikut muncul kalau bon
    // di-convert ke array/JSON (misal dikirim ke frontend untuk export).
    protected $appends = ['total_kwitansi'];

    public function sie()
    {
        return $this->belongsTo(Sie::class, 'ID_Sie', 'ID_Sie');
    }

    // 1 Bon bisa punya banyak Item, selama Item itu Sie-nya sama
    public function items()
    {
        return $this->hasMany(Item::class, 'ID_Bon', 'ID_Bon');
    }

    public function itemLpj()
    {
        return $this->hasMany(item_lpj::class, 'ID_Bon', 'ID_Bon');
    }

    /**
     * Bon hanya tampil lewat item-itemnya, jadi bon tanpa item satu pun jadi data
     * yatim. Dipanggil setelah item terakhirnya dihapus / dipindah.
     */
    public function hapusJikaKosong(): bool
    {
        if ($this->itemLpj()->exists() || $this->items()->exists()) {
            return false;
        }

        if ($this->Foto_Bon && File::exists(public_path('uploads/bon/'.$this->Foto_Bon))) {
            File::delete(public_path('uploads/bon/'.$this->Foto_Bon));
        }

        return (bool) $this->delete();
    }

    /**
     * Total kwitansi = penjumlahan kolom Total dari semua Item yang
     * terhubung ke Bon ini. TIDAK disimpan di database, selalu dihitung
     * ulang supaya tidak pernah "nyasar"/tidak sinkron dengan item aslinya.
     *
     * Query tunggal (misal detail 1 bon):
     *   $bon->total_kwitansi
     *
     * Query banyak bon sekaligus (dipakai saat export LPJ, lebih efisien
     * karena tidak N+1 query — 1 query agregat untuk semua bon):
     *   Bon::withSum('items as total_kwitansi', 'Total')->get();
     */
    public function getTotalKwitansiAttribute(): float
    {
        // Kalau sudah di-load lewat withSum(), pakai itu supaya tidak query ulang.
        if (array_key_exists('total_kwitansi', $this->attributes)) {
            return (float) $this->attributes['total_kwitansi'];
        }

        return (float) $this->items()->sum('Total');
    }
}