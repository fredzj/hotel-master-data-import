<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApaleoUnitGroup extends Model
{
    protected $fillable = [
        'apaleo_id',
        'property_id',
        'code',
        'name',
        'description',
        'type',
        'max_persons',
        'member_count',
        'raw_data',
    ];

    protected $casts = [
        'raw_data' => 'array',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(ApaleoProperty::class, 'property_id', 'apaleo_id');
    }

    public function units(): HasMany
    {
        return $this->hasMany(ApaleoUnit::class, 'unit_group_id', 'apaleo_id');
    }
}