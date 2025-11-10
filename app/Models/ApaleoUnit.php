<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApaleoUnit extends Model
{
    protected $fillable = [
        'apaleo_id',
        'property_id',
        'unit_group_id',
        'name',
        'description',
        'status',
        'condition',
        'max_persons',
        'size',
        'view',
        'raw_data',
    ];

    protected $casts = [
        'raw_data' => 'array',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(ApaleoProperty::class, 'property_id', 'apaleo_id');
    }

    public function unitGroup(): BelongsTo
    {
        return $this->belongsTo(ApaleoUnitGroup::class, 'unit_group_id', 'apaleo_id');
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(ApaleoUnitAttribute::class, 'unit_id', 'apaleo_id');
    }
}