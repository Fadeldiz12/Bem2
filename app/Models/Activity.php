<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'created_by','title','borrower_name','description',
        'activity_date','start_time','end_time','location','status',
    ];
    protected $casts = ['activity_date' => 'date'];

    public function creator() { return $this->belongsTo(User::class, 'created_by'); }

    public static function hasConflict(string $location, string $date, ?string $start, ?string $end, ?int $excludeId = null): ?self
    {
        return self::where('location', $location)
            ->where('activity_date', $date)
            ->where('status', '!=', 'ditolak')
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->when($start && $end, fn($q) => $q->where('start_time', '<', $end)->where('end_time', '>', $start))
            ->first();
    }

    public static function statusLabel(string $s): string
    {
        return match($s) { 'menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak', default => $s };
    }
    public static function statusColor(string $s): string
    {
        return match($s) { 'menunggu' => '#f59e0b', 'disetujui' => '#10b981', 'ditolak' => '#ef4444', default => '#6b7280' };
    }
}
