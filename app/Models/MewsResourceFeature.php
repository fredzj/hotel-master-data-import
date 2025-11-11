<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MewsResourceFeature extends Model
{
    protected $table = 'mews_resource_features';

    protected $fillable = [
        'mews_id',
        'service_id',
        'external_identifier',
        'name',
        'description',
        'is_active',
        'classification',
        'raw_data',
        'mews_created_utc',
        'mews_updated_utc',
        'last_imported_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'raw_data' => 'array',
        'mews_created_utc' => 'datetime',
        'mews_updated_utc' => 'datetime',
        'last_imported_at' => 'datetime',
    ];

    // Relationships
    public function service(): BelongsTo
    {
        return $this->belongsTo(MewsService::class, 'service_id', 'mews_id');
    }

    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(
            MewsResource::class,
            'mews_resource_feature_assignments',
            'resource_feature_id',
            'resource_id',
            'mews_id',
            'mews_id'
        );
    }

    public function enterprise()
    {
        return $this->service?->enterprise();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByClassification($query, $classification)
    {
        return $query->where('classification', $classification);
    }
}
