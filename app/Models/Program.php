<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = ['department_id','name','description','execution_date','status','sort_order'];
    protected $casts    = ['execution_date' => 'date'];

    public function department() { return $this->belongsTo(Department::class); }

    public static function statusLabel(string $status): string
    {
        return match($status) {
            'akan_dilaksanakan' => 'Akan Dilaksanakan',
            'sedang_berjalan'   => 'Sedang Berjalan',
            'selesai'           => 'Selesai',
            default             => $status,
        };
    }
}