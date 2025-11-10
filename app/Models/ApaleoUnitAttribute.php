<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApaleoUnitAttribute extends Model
{
    protected $fillable = [
        'unit_id',
        'name',
        'value',
        'type',
        'unit_of_measure',
        'raw_data',
    ];

    protected $casts = [
        'raw_data' => 'array',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(ApaleoUnit::class, 'unit_id', 'apaleo_id');
    }
}