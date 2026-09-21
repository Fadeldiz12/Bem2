<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Kegiatan;
use App\Models\Item;
use App\Models\Bon;

class Sie extends Model
{
    protected $table = 'Sie';
    protected $primaryKey = 'ID_Sie';

    protected $fillable = [
        'ID_Kegiatan', 
        'Nama_Sie'
    ];
    

    public function kegiatan() : BelongsTo
    {
        return $this->belongsTo(Kegiatan::class, 'ID_Kegiatan', 'ID_Kegiatan');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'ID_Sie', 'ID_Sie');
    }

    public function item_lpj() : HasMany
    {
        return $this->hasMany(item_lpj::class, 'ID_Sie', 'ID_Sie');
    }

    public function bons()
    {
        return $this->hasMany(Bon::class, 'ID_Sie', 'ID_Sie');
    }
}