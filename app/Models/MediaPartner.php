<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaPartner extends Model
{
    protected $fillable = ['type','title','procedures','gform_link'];

    public function getProceduresListAttribute(): array
    {
        return json_decode($this->procedures ?? '[]', true) ?: [];
    }
}
