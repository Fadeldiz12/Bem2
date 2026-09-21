<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    protected $table = 'Proposal';
    protected $primaryKey = 'ID_Proposal';
    public $timestamps = false;

    protected $fillable = [
        'Nama_Kegiatan',
        'Deskripsi_Kegiatan',
        'Tanggal_Kegiatan',
        'Anggaran',
        'Status',
        'ID_Pengguna',
    ];
}
