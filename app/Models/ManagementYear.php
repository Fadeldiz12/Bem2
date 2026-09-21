<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagementYear extends Model
{
    protected $fillable = [
        'year_label','cabinet_name','tagline','logo_path','visi','misi',
        'filosofi_logo','makna_warna',
        'presma_name','presma_photo','wapresma_name','wapresma_photo',
        'status','is_active','start_date','end_date',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function ministries() { return $this->hasMany(Ministry::class); }
    public function members()    { return $this->hasMany(Member::class); }

    public static function getActive(): ?self
    {
        return self::where('is_active', true)->where('status', 'published')->first();
    }

    public static function publish(int $id): void
    {
        self::where('id', '!=', $id)->where('status', 'published')
            ->update(['is_active' => false, 'status' => 'archived']);
        self::where('id', $id)->update(['is_active' => true, 'status' => 'published']);
    }

    public function getMisiListAttribute(): array
    {
        return json_decode($this->misi ?? '[]', true) ?: [];
    }

    public function getFilosofiListAttribute(): array
    {
        return json_decode($this->filosofi_logo ?? '[]', true) ?: [];
    }

    public function getWarnaListAttribute(): array
    {
        return json_decode($this->makna_warna ?? '[]', true) ?: [];
    }
}