<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MewsResourceCategory extends Model
{
    protected $table = 'mews_resource_categories';

    protected $fillable = [
        'mews_id',
        'service_id',
        'external_identifier',
        'name',
        'description',
        'is_active',
        'type',
        'normal_bed_count',
        'extra_bed_count',
        'included_persons',
        'capacity',
        'ordering',
        'area',
        'raw_data',
        'mews_created_utc',
        'mews_updated_utc',
        'last_imported_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'normal_bed_count' => 'integer',
        'extra_bed_count' => 'integer',
        'included_persons' => 'integer',
        'capacity' => 'integer',
        'area' => 'decimal:2',
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
            'mews_resource_category_assignments',
            'resource_category_id',
            'resource_id',
            'mews_id',
            'mews_id'
        );
    }

    public function enterprise()
    {
        return $this->service->enterprise();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Accessors
    public function getTotalCapacityAttribute(): int
    {
        return ($this->normal_bed_count ?? 0) + ($this->extra_bed_count ?? 0);
    }

    public function getFormattedAreaAttribute(): string
    {
        return $this->area ? $this->area . ' m²' : 'N/A';
    }

    // Constants for room types
    public const TYPES = [
        'Room' => 'Room',
        'Bed' => 'Bed',
        'Dorm' => 'Dorm',
        'Apartment' => 'Apartment',
        'Suite' => 'Suite',
        'Villa' => 'Villa',
        'Site' => 'Site',
        'Office' => 'Office',
        'MeetingRoom' => 'Meeting Room',
        'ParkingSpot' => 'Parking Spot',
        'Desk' => 'Desk',
        'TeamArea' => 'Team Area',
        'Membership' => 'Membership',
        'Tent' => 'Tent',
        'CaravanOrRV' => 'Caravan or RV',
        'UnequippedCampsite' => 'Unequipped Campsite',
        'Bike' => 'Bike',
        'ExtraBed' => 'Extra Bed',
        'Cot' => 'Cot',
        'Crib' => 'Crib',
        'ConferenceRoom' => 'Conference Room',
        'Rooftop' => 'Rooftop',
        'Garden' => 'Garden',
        'Restaurant' => 'Restaurant',
        'Amphitheater' => 'Amphitheater',
        'PrivateSpaces' => 'Private Spaces',
    ];
}
