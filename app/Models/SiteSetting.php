<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key','value','type','label'];

    public static function getValue(string $key, string $default = ''): string
    {
        return self::where('key', $key)->value('value') ?? $default;
    }

    public static function allAsArray(): array
    {
        return self::all()->pluck('value', 'key')->toArray();
    }

    public static function saveValue(string $key, ?string $value): void
    {
        self::where('key', $key)->update(['value' => $value ?? '']);
    }
}
