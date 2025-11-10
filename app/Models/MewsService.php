<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MewsService extends Model
{
    protected $table = 'mews_services';

    protected $fillable = [
        'mews_id',
        'enterprise_id',
        'external_identifier',
        'name',
        'is_active',
        'bill_as_package',
        'data_discriminator',
        'start_offset',
        'end_offset',
        'occupancy_start_offset',
        'occupancy_end_offset',
        'time_unit_period',
        'promotion_before_checkin',
        'promotion_after_checkin',
        'promotion_during_stay',
        'promotion_before_checkout',
        'promotion_after_checkout',
        'promotion_during_checkout',
        'raw_data',
        'mews_created_utc',
        'mews_updated_utc',
        'last_imported_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'bill_as_package' => 'boolean',
        'promotion_before_checkin' => 'boolean',
        'promotion_after_checkin' => 'boolean',
        'promotion_during_stay' => 'boolean',
        'promotion_before_checkout' => 'boolean',
        'promotion_after_checkout' => 'boolean',
        'promotion_during_checkout' => 'boolean',
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

    public function resourceCategories(): HasMany
    {
        return $this->hasMany(MewsResourceCategory::class, 'service_id', 'mews_id');
    }

    public function resourceFeatures(): HasMany
    {
        return $this->hasMany(MewsResourceFeature::class, 'service_id', 'mews_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBookable($query)
    {
        return $query->where('data_discriminator', 'Bookable');
    }

    public function scopeAdditional($query)
    {
        return $query->where('data_discriminator', 'Additional');
    }

    // Accessors
    public function getIsBookableAttribute(): bool
    {
        return $this->data_discriminator === 'Bookable';
    }

    public function getIsAdditionalAttribute(): bool
    {
        return $this->data_discriminator === 'Additional';
    }

    public function hasPromotions(): bool
    {
        return $this->promotion_before_checkin || 
               $this->promotion_after_checkin || 
               $this->promotion_during_stay || 
               $this->promotion_before_checkout || 
               $this->promotion_after_checkout || 
               $this->promotion_during_checkout;
    }
}
