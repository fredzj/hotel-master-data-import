<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MewsEnterprise extends Model
{
    protected $table = 'mews_enterprises';

    protected $fillable = [
        'mews_id',
        'external_identifier',
        'holding_key',
        'chain_id',
        'chain_name',
        'name',
        'time_zone_identifier',
        'legal_environment_code',
        'accommodation_environment_code',
        'accounting_environment_code',
        'tax_environment_code',
        'default_language_code',
        'pricing',
        'tax_precision',
        'website_url',
        'email',
        'phone',
        'logo_image_id',
        'cover_image_id',
        'address_id',
        'address_line1',
        'address_line2',
        'city',
        'postal_code',
        'country_code',
        'country_subdivision_code',
        'latitude',
        'longitude',
        'tax_identifier',
        'raw_data',
        'linked_utc',
        'mews_created_utc',
        'mews_updated_utc',
        'last_imported_at',
    ];

    protected $casts = [
        'raw_data' => 'array',
        'linked_utc' => 'datetime',
        'mews_created_utc' => 'datetime',
        'mews_updated_utc' => 'datetime',
        'last_imported_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'tax_precision' => 'integer',
    ];

    // Relationships
    public function services(): HasMany
    {
        return $this->hasMany(MewsService::class, 'enterprise_id', 'mews_id');
    }

    public function resources(): HasMany
    {
        return $this->hasMany(MewsResource::class, 'enterprise_id', 'mews_id');
    }

    // Accessors
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_line1,
            $this->address_line2,
            $this->city,
            $this->postal_code,
            $this->country_code,
        ]);

        return implode(', ', $parts);
    }

    public function getAccommodationServiceAttribute()
    {
        return $this->services()->where('data_discriminator', 'Bookable')->first();
    }
}
