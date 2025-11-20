<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sunbed extends Model
{
    protected $table = 'transformed_sunbeds';

    protected $fillable = [
        'sunbed_area_id',
        'sunbed_type_id',
        'name',
        'identifier',
        'status',
    ];

    public function sunbedArea(): BelongsTo
    {
        return $this->belongsTo(SunbedArea::class);
    }

    public function sunbedType(): BelongsTo
    {
        return $this->belongsTo(SunbedType::class);
    }

    // Helper method to get the hotel through sunbed area
    public function hotel()
    {
        return $this->sunbedArea->hotel();
    }
}
