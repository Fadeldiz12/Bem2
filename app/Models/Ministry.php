<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ministry extends Model
{
    protected $fillable = [
        'management_year_id','name','alias','logo_path','description',
        'tugas_pokok','whatsapp_number','whatsapp_label','sort_order',
    ];

    public function managementYear() { return $this->belongsTo(ManagementYear::class); }
    public function departments()    { return $this->hasMany(Department::class)->orderBy('sort_order'); }
    public function members()        { return $this->hasMany(Member::class); }
    public function menteri()        { return $this->hasOne(Member::class)->where('role', 'menteri'); }
}
