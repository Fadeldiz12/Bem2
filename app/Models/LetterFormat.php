<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterFormat extends Model
{
    protected $fillable = [
        'uploaded_by', 'title', 'description', 'icon_type',
        'file_path', 'file_type',
        'file_path_hmps', 'file_type_hmps',
        'file_path_ukm', 'file_type_ukm',
        'download_count', 'is_active', 'sort_order'
    ];
    protected $casts    = ['is_active' => 'boolean'];
    public function uploader() { return $this->belongsTo(User::class, 'uploaded_by'); }
}
