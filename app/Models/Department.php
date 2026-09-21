<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['ministry_id','name','description','tugas_pokok','sort_order'];

    public function ministry()  { return $this->belongsTo(Ministry::class); }
    public function members()   { return $this->hasMany(Member::class)->orderBy('sort_order'); }
    public function head()      { return $this->hasOne(Member::class)->where('is_head', true); }
    public function staff()     { return $this->hasMany(Member::class)->where('is_head', false)->orderBy('sort_order'); }
    public function programs()  { return $this->hasMany(Program::class)->orderBy('sort_order'); }
}
