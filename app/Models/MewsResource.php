<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MewsResource extends Model
{
    protected $table = 'mews_resources';

    protected $fillable = [
        'mews_id',
        'enterprise_id',
        'parent_resource_id',
        'category_id',
        'name',
        'is_active',
        'state',
        'state_reason',
        'descriptions',
        'external_names',
        'directions',
        'data_discriminator',
        'floor_number',
        'building_number',
        'location_notes',
        'external_identifier',
        'description',
        'raw_data',
        'mews_created_utc',
        'mews_updated_utc',
        'last_imported_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'descriptions' => 'array',
        'external_names' => 'array',
        'directions' => 'array',
        'raw_data' => 'array',
        'mews_created_utc' => 'datetime',
        'mews_updated_utc' => 'datetime',
        'last_imported_at' => 'datetime',
    ];

    // Relationships
    public function enterprise(): BelongsTo
    {
        return $this->belongsTo(MewsEnterprise::class, 'enterprise_id', 'mews_id');
    }

    public function parentResource(): BelongsTo
    {
        return $this->belongsTo(MewsResource::class, 'parent_resource_id', 'mews_id');
    }

    public function childResources(): HasMany
    {
        return $this->hasMany(MewsResource::class, 'parent_resource_id', 'mews_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            MewsResourceCategory::class,
            'mews_resource_category_assignments',
            'resource_id',
            'resource_category_id',
            'mews_id',
            'mews_id'
        );
    }

    // Convenience accessor for the first category (for backward compatibility)
    public function getCategoryAttribute()
    {
        return $this->categories()->first();
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(
            MewsResourceFeature::class,
            'mews_resource_feature_assignments',
            'resource_id',
            'resource_feature_id',
            'mews_id',
            'mews_id'
        );
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByState($query, $state)
    {
        return $query->where('state', $state);
    }

    public function scopeSpaces($query)
    {
        return $query->where('data_discriminator', 'Space');
    }

    public function scopeObjects($query)
    {
        return $query->where('data_discriminator', 'Object');
    }

    public function scopePersons($query)
    {
        return $query->where('data_discriminator', 'Person');
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_resource_id');
    }

    // Accessors
    public function getIsSpaceAttribute(): bool
    {
        return $this->data_discriminator === 'Space';
    }

    public function getIsObjectAttribute(): bool
    {
        return $this->data_discriminator === 'Object';
    }

    public function getIsPersonAttribute(): bool
    {
        return $this->data_discriminator === 'Person';
    }

    public function getDescriptionAttribute(): string
    {
        if (!$this->descriptions) {
            return '';
        }

        // Try to get English description first, then fall back to first available
        return $this->descriptions['en-US'] ?? 
               $this->descriptions['en'] ?? 
               collect($this->descriptions)->first() ?? 
               '';
    }

    public function getExternalNameAttribute(): string
    {
        if (!$this->external_names) {
            return $this->name;
        }

        // Try to get English name first, then fall back to first available
        return $this->external_names['en-US'] ?? 
               $this->external_names['en'] ?? 
               collect($this->external_names)->first() ?? 
               $this->name;
    }

    public function getDirectionAttribute(): string
    {
        if (!$this->directions) {
            return '';
        }

        // Try to get English directions first, then fall back to first available
        return $this->directions['en-US'] ?? 
               $this->directions['en'] ?? 
               collect($this->directions)->first() ?? 
               '';
    }

    public function getStateColorAttribute(): string
    {
        return match ($this->state) {
            'Clean' => 'success',
            'Inspected' => 'info',
            'Dirty' => 'warning',
            'OutOfService' => 'secondary',
            'OutOfOrder' => 'danger',
            default => 'primary',
        };
    }

    // Constants for resource states
    public const STATES = [
        'Dirty' => 'Dirty',
        'Clean' => 'Clean',
        'Inspected' => 'Inspected',
        'OutOfService' => 'Out of Service',
        'OutOfOrder' => 'Out of Order',
    ];

    // Constants for resource types
    public const DISCRIMINATORS = [
        'Space' => 'Space',
        'Object' => 'Object',
        'Person' => 'Person',
    ];
}
