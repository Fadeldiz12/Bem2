<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Kegiatan extends Model
{
    protected $table = 'Kegiatan';

    protected $primaryKey = 'ID_Kegiatan';

    public $timestamps = false;

    protected $fillable = [
        'Nama_Kegiatan',
        'Deskripsi_Kegiatan',
        'Tanggal_Pelaksanaan',
        'Jenis_RAB',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        // Foreign key ditulis eksplisit (sebelumnya belongsTo(User::class)
        // tanpa key eksplisit akan salah menebak nama kolomnya).
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sie(): HasMany
    {
        return $this->hasMany(Sie::class, 'ID_Kegiatan', 'ID_Kegiatan');
    }

    public function items(): HasManyThrough
    {
        // Kegiatan memiliki banyak Item melalui Sie
        return $this->hasManyThrough(
            Item::class,     // Model tujuan akhir
            Sie::class,      // Model perantara
            'ID_Kegiatan',   // Foreign key di tabel Sie
            'ID_Sie',        // Foreign key di tabel Item
            'ID_Kegiatan',   // Local key di tabel Kegiatan
            'ID_Sie'         // Local key di tabel Sie
        );
    }

    public function itemsLPJ(): HasManyThrough
    {
        return $this->hasManyThrough(
            item_lpj::class, // Model tujuan akhir
            Sie::class,      // Model perantara
            'ID_Kegiatan',   // Foreign key di tabel Sie
            'ID_Sie',        // Foreign key di tabel Item
            'ID_Kegiatan',   // Local key di tabel Kegiatan
            'ID_Sie'         // Local key di tabel Sie
        );
    }
}
