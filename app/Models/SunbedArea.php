<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SunbedArea extends Model
{
    protected $table = 'transformed_sunbed_areas';

    protected $fillable = [
        'hotel_id',
        'name',
        'location_description',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function sunbeds(): HasMany
    {
        return $this->hasMany(Sunbed::class);
    }
}
