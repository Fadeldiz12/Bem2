<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'author_id','title','slug','content','featured_image',
        'content_image_1','content_image_2','content_image_3',
        'status','category','published_at'
    ];
    protected $casts    = ['published_at' => 'datetime'];

    public function author() { return $this->belongsTo(User::class, 'author_id'); }

    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = self::where('slug', $slug)->count();
        return $count ? $slug . '-' . time() : $slug;
    }
}
