<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'management_year_id','ministry_id','department_id',
        'full_name','nim','position','role','is_head','photo_path','sort_order',
    ];
    protected $casts = ['is_head' => 'boolean'];

    public function managementYear() { return $this->belongsTo(ManagementYear::class); }
    public function ministry()       { return $this->belongsTo(Ministry::class); }
    public function department()     { return $this->belongsTo(Department::class); }
}
