<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SunbedType extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function sunbeds(): HasMany
    {
        return $this->hasMany(Sunbed::class);
    }
}
